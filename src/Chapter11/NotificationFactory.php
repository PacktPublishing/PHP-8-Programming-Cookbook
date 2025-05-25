<?php

namespace Cookbook\Chapter11;

use Cookbook\Chapter11\Medium\EmailNotification;
use Cookbook\Chapter11\Medium\SmsNotification;
use Cookbook\Chapter11\Models\Contact;

class NotificationFactory
{
    private array $config;

    public function __construct()
    {
        $this->setConfig();
    }

    public function create(NotificationType $type): NotificationInterface
    {
        return new $type->value($this->getConfig()[$type->value]);
    }

    private function setConfig()
    {
        $emailSender = new Contact();
        $emailSender->setEmail('dummy@example.com');
        $emailSender->setName('John Doe');

        $this->config = [
            SmsNotification::class => [
                'api_key' => 'dummy_api_key',
                'sender_id' => 'dummy_sender_id',
            ],
            EmailNotification::class => [
                'sender' => $emailSender,
            ],
        ];
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}