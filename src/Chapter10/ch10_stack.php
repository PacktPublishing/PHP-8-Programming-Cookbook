<?php

use Cookbook\Chapter10\Stack\BankTransaction;

include __DIR__ . '/../../vendor/autoload.php';

$initialAccounts = [
    "sender_account" => 20000.00,
    "recipient_account" => 15000.00,
];
$bank = new BankTransaction($initialAccounts);

// Starting Balances:
echo "||| Starting Balances: |||\n";
$bank->printAccounts();
echo "\n";

$bank->transferFunds("sender_account", "recipient_account", 5555.00);
$bank->transferFunds("sender_account", "recipient_account", 253.00);
$bank->transferFunds("sender_account", "recipient_account", 30000.00); // Will cause Rollback through Stack

// Closing Balances:
echo "||| Closing Balances: |||\n";
$bank->printAccounts();
echo "\n";