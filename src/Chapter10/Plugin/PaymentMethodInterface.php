<?php

namespace Cookbook\Chapter10\Plugin;

interface PaymentMethodInterface
{
    public function send(float $amount, string $sender, string $recipient): array;
}