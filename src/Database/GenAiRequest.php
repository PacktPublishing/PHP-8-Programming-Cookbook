<?php
namespace Cookbook\Database;
use PDO;
use Exception;
use DateInterval;
use Psr\SimpleCache\CacheInterface;
#[GenAiRequest("Processes GenAI request")]
class GenAiRequest
{
    public const THRESHOLD   = 90;
    public const NO_RESPONSE = 'No response from your AI platform';
    public const FROM_CACHE  = 'Returned from cache: ' . PHP_EOL;
    public const FROM_ORIG   = 'Returned from GenAI: ' . PHP_EOL;
    public const AI_KEY_FN   = __DIR__ . '/../../secure/monica_api_key.txt';
    public const AI_MODEL    = 'gpt-4.1-nano';     // $0.10 / 1M input tokens | $0.40 / 1M output tokens
    public const AI_API_URL  = 'https://openapi.monica.im/v1/chat/completions';
    public const AI_SYS_TEXT = 'Keep your responses concise and limited to 256 words or less.';
    public function __construct(
        public ?CacheInterface $cache = NULL)
    {}
    #[GenAiRequest\__invoke(
        "@param string \$request",
        "@return string \$response")]
    public function __invoke(string $request) : string
    {
        $request = trim(strip_tags($request));  // NOTE: doesn't protect against prompt injection attacks
        $key = $this->createKey($request);
        $text = $this->cache->get($key);
        $prefix = self::FROM_CACHE;
        if (empty($text)) {
            // make GenAI call
            $text = $this->makeCall($request);
            // cache result
            $this->cache->set($key, $text, new DateInterval('P1W'));
            $prefix = self::FROM_ORIG;
        }
        return $prefix . $text;
    }
    // curl example:
    /*
    curl https://openapi.monica.im/v1/chat/completions \
      -H "Content-Type: application/json" \
      -H "Authorization: Bearer $YOUR_API_KEY" \
      -d '{
      "model": "gpt-4o",
      "messages": [
        {
          "role": "user",
          "content": [{"type": "text", "text": "Hi!"}]
        }
      ]
    }'
    */
    protected function makeCall(string $request) : string
    {
        // get API key
        $apiKey = trim(file_get_contents(static::AI_KEY_FN));
        // set up GenAI API data
        $data = [
            'model' => static::AI_MODEL,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [['type' => 'text', 'text' => static::AI_SYS_TEXT . ' ' . $request]],
                ]
            ]
        ];
        $json = json_encode($data);
        // make the request to GenAI
        $ch = curl_init(static::AI_API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);
        // Set these to TRUE in production!!!
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Don't verify the peer's SSL certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // Don't verify the certificate's name against host
        // Make the call
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if (!empty($error)) {
            throw new Exception('ERROR ' . __LINE__ . ' [' . $error . ']');
        }
        
        if ($httpCode !== 200) {
            throw new Exception('ERROR ' . __LINE__ . ' [HTTP:' . $httpCode . '] ');
        }
        $text = json_decode($response, TRUE)['choices'][0]['message']['content'] ?? '';
        if (empty($text)) {
            throw new Exception(static::NO_RESPONSE);
        }
        return $text;
    }
    protected function createKey(string $text)
    {
        return md5($text);
    }
}
