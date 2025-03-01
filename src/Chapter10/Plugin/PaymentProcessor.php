<?php

namespace Cookbook\Chapter10\Plugin;

use Exception;

class PaymentProcessor
{
    private array $paymentMethods = [];

    public function registerPaymentMethod(string $name, PaymentMethodInterface $paymentMethod): PaymentProcessor
    {
        $this->paymentMethods[$name] = $paymentMethod;
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (isset($this->paymentMethods[$name])) {
            return $this->paymentMethods[$name]->send(...$arguments);
        }

        return [
            'success' => 0,
            'error' => "❌ Payment method {$name} does not exist."
        ];
    }
}