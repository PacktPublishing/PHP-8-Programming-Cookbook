<?php

namespace Cookbook\Chapter11\Medium;

use Cookbook\Chapter11\Models\Contact;
use Cookbook\Chapter11\NotificationInterface;

class EmailNotification implements NotificationInterface
{
    private string $headers;

    public function __construct(array $config)
    {
        $sender = $config['sender'];
        $fromName = $sender->getName();
        $fromEmail = $sender->getEmail();

        $fromHeader = $fromName
            ? "$fromName <$fromEmail>"
            : $fromEmail;

        $this->headers = "From: $fromHeader\r\n";
        $this->headers .= "Reply-To: $fromEmail\r\n";
        $this->headers .= "MIME-Version: 1.0\r\n";
        $this->headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    }

    public function send(Contact $sender, Contact $receiver, string $message, array $options): bool
    {
        return mail($receiver->getEmail(), $options['subject'], $message, $this->headers);
    }
}