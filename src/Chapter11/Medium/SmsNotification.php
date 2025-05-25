<?php

namespace Cookbook\Chapter11\Medium;

use Cookbook\Chapter11\Models\Contact;
use Cookbook\Chapter11\NotificationInterface;

class SmsNotification implements NotificationInterface
{
    private string $apiUrl;
    private string $apiKey;
    private string $senderId;

    public function __construct(array $config)
    {
        // Dummy config:
        $this->apiUrl   = 'https://api.example.com/SMS';
        $this->apiKey   = $config['api_key'];
        $this->senderId = $config['sender_id'];
    }

    public function send(Contact $sender, Contact $receiver, string $message, array $options): bool
    {
        $postData = http_build_query([
            'api_key'   => $this->apiKey,
            'to'        => $receiver->getPhone(),
            'sender'    => $this->senderId,
            'message'   => $message,
        ]);

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $postData,
                'timeout' => 10,
            ]
        ]);

        $result = file_get_contents($this->apiUrl, false, $context);

        return $result !== false;
    }
}