<?php

namespace Cookbook\Chapter11;

use Cookbook\Chapter11\Models\Contact;

interface NotificationInterface
{
    public function send(Contact $sender, Contact $receiver, string $message, array $options): bool;
}