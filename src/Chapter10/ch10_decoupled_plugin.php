<?php

use \Cookbook\Chapter10\Plugin\PaymentProcessor;
use \Cookbook\Chapter10\Plugin\PayPalMethod;
use \Cookbook\Chapter10\Plugin\WiseMethod;

include __DIR__ . '/../../vendor/autoload.php';

$paymentProcessor = new PaymentProcessor();
$paypalMethod = new PayPalMethod();
$wiseMethod = new WiseMethod();

// Can be refactored and placed into a configuration module:
$paymentProcessor
    ->registerPaymentMethod("sendToPayPal", $paypalMethod) // Register PayPal class
    ->registerPaymentMethod("sendToWise", $wiseMethod); // Register Wise class
// Can easily add more methods...

print_r($paymentProcessor->sendToPayPal(150, "john@doe.com", "paypal@recipient.com"));
echo "\n";
print_r($paymentProcessor->sendToWise(64.23, "jane@doe.com", "wise@recipient.com"));
echo "\n";
print_r($paymentProcessor->sendToMagicMoney(200, "jane@doe.com", "magic@recipient.com"));
echo "\n";