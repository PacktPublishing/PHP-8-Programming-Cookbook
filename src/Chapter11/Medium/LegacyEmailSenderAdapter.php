<?php

namespace Cookbook\Chapter11\Medium;

use Cookbook\Chapter11\Models\Contact;
use Cookbook\Chapter11\NotificationInterface;
use Cookbook\Chapter11\OldMedium\LegacyEmailSender;

class LegacyEmailSenderAdapter implements NotificationInterface
{
    private LegacyEmailSender $target;

    public function __construct(LegacyEmailSender $emailSender)
    {
        $this->target = $emailSender;
    }

    public function send(Contact $sender, Contact $receiver, string $message, array $options): bool
    {
        // Adapt the data to the old format:
        $options = [
            'sender_email' => $sender->getEmail(),
            'receiver_email' => $receiver->getEmail(),
            'message' => $message,
            'subject' => $options['subject'],
        ];

        return $this->target->SendEmail($options);
    }

}