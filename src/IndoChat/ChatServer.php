<?php
namespace Cookbook\IndoChat;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Cookbook\IndoChat\Platform\PlatformInterface;

class ChatServer implements MessageComponentInterface
{
    /** @var array<int, array{conn: ConnectionInterface, username: string|null, language: string}> */
    private array $clients = [];
    private string $usersFile;
    private PlatformInterface $platform;
    public function __construct(string $usersFile, PlatformInterface $platform)
    {
        $this->usersFile = $usersFile;

        $dir = dirname($usersFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($usersFile, '[]');

        if (empty(API_KEY)) {
            echo "[WARNING] API_KEY is not set. Translation will not work.\n";
        }

        $this->platform = $platform;
        echo "IndoChat WebSocket server started on port " . WS_PORT . "\n";
        echo "Press Ctrl+C to stop.\n\n";
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients[$conn->resourceId] = [
            'conn'     => $conn,
            'username' => null,
            'language' => 'en',
        ];
        echo "[connect]   #{$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);
        if (!is_array($data) || empty($data['type'])) {
            return;
        }

        match ($data['type']) {
            'set_user'     => $this->handleSetUser($from, $data),
            'send_message' => $this->handleSendMessage($from, $data),
            default        => null,
        };
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $username = $this->clients[$conn->resourceId]['username'] ?? null;
        unset($this->clients[$conn->resourceId]);
        $this->writeUsersFile();
        $this->broadcastUsersList();
        echo "[disconnect] #{$conn->resourceId}" . ($username ? " ({$username})" : '') . "\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "[error]     #{$conn->resourceId}: {$e->getMessage()}\n";
        $conn->close();
    }

    // -------------------------------------------------------------------------

    private function handleSetUser(ConnectionInterface $conn, array $data): void
    {
        $username = trim($data['username'] ?? '');
        $language = in_array($data['language'] ?? '', ['en', 'km'], true) ? $data['language'] : 'en';

        if ($username === '' || strlen($username) > 30) {
            $this->send($conn, ['type' => 'error', 'message' => 'Username must be 1–30 characters.']);
            return;
        }

        // Conflict check: another connection already holds this name
        foreach ($this->clients as $id => $client) {
            if ($id !== $conn->resourceId && $client['username'] === $username) {
                $this->send($conn, ['type' => 'error', 'message' => "Username \"{$username}\" is already taken."]);
                return;
            }
        }

        $this->clients[$conn->resourceId]['username'] = $username;
        $this->clients[$conn->resourceId]['language'] = $language;

        $this->send($conn, ['type' => 'user_set', 'username' => $username, 'language' => $language]);
        $this->writeUsersFile();
        $this->broadcastUsersList();

        $langLabel = $language === 'km' ? 'Khmer' : 'English';
        echo "[user_set]  #{$conn->resourceId} → {$username} ({$langLabel})\n";
    }

    private function handleSendMessage(ConnectionInterface $from, array $data): void
    {
        $toUsername = $data['to']      ?? '';
        $message    = trim($data['message'] ?? '');

        if ($message === '' || $toUsername === '') {
            return;
        }

        $fromClient = $this->clients[$from->resourceId];
        if ($fromClient['username'] === null) {
            $this->send($from, ['type' => 'error', 'message' => 'Set a username before sending messages.']);
            return;
        }

        $toClient = $this->findClientByUsername($toUsername);
        if ($toClient === null) {
            $this->send($from, ['type' => 'error', 'message' => "\"{$toUsername}\" is no longer connected."]);
            return;
        }

        $fromLang   = $fromClient['language'];
        $toLang     = $toClient['language'];
        $translated = ($fromLang !== $toLang) ? $this->translate($message, $fromLang, $toLang) : $message;

        $payload = [
            'type'       => 'message',
            'from'       => $fromClient['username'],
            'to'         => $toUsername,
            'original'   => $message,
            'translated' => $translated,
            'fromLang'   => $fromLang,
            'toLang'     => $toLang,
            'timestamp'  => date('H:i'),
        ];

        $this->send($from, $payload);
        $this->send($toClient['conn'], $payload);

        echo "[message]   {$fromClient['username']} → {$toUsername}\n";
    }

    // -------------------------------------------------------------------------

    private function translate(string $text, string $fromLang, string $toLang): string
    {
        if (empty(API_KEY)) {
            return '[Translation unavailable — API_KEY not set]';
        }

        $names = ['en' => 'English', 'km' => 'Khmer'];

        $body = json_encode([
            'model'        => AI_MODEL,
            'instructions' => 'You are a professional translator. Return only the translated text — no explanations, no notes, no punctuation changes beyond what translation requires.',
            'input'        => "Translate from {$names[$fromLang]} to {$names[$toLang]}:\n\n{$text}",
        ]);

        $ch = curl_init(API_ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . API_KEY,
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $raw   = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        error_log(__METHOD__ . ':' . $raw);
        if ($error) {
            echo "[translate] cURL error: {$error}\n";
            return '[Translation error]';
        }

        $result = json_decode($raw, true);
        return $this->platform->get($result) ?? 'Translation failed';
    }

    // -------------------------------------------------------------------------

    private function findClientByUsername(string $username): ?array
    {
        foreach ($this->clients as $client) {
            if ($client['username'] === $username) {
                return $client;
            }
        }
        return null;
    }

    private function connectedUsers(): array
    {
        return array_values(array_filter(array_map(
            fn(array $c): ?array => $c['username']
                ? ['username' => $c['username'], 'language' => $c['language']]
                : null,
            $this->clients
        )));
    }

    private function writeUsersFile(): void
    {
        file_put_contents($this->usersFile, json_encode($this->connectedUsers()));
    }

    private function broadcastUsersList(): void
    {
        $payload = json_encode(['type' => 'users_list', 'users' => $this->connectedUsers()]);
        foreach ($this->clients as $client) {
            $client['conn']->send($payload);
        }
    }

    private function send(ConnectionInterface $conn, array $data): void
    {
        $conn->send(json_encode($data));
    }
}
