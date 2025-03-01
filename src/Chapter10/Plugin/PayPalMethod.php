<?php

namespace Cookbook\Chapter10\Plugin;

class PayPalMethod implements PaymentMethodInterface
{
    public function send(float $amount, string $sender, string $recipient): array
    {
        // Some logic here

        return [
            'success' => true,
            'amount' => $amount,
            'sender' => $sender,
            'recipient' => $recipient,
            'method' => 'PAYPAL',
        ];
    }
}