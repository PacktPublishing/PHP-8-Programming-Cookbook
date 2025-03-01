<?php

namespace Cookbook\Chapter10\Stack;

class BankTransaction
{
    private array $accounts;
    private Stack $rollbackStack;

    public function __construct(array $initialAccounts)
    {
        $this->accounts = $initialAccounts;
        $this->rollbackStack = new Stack();
    }

    public function transferFunds(string $fromAccount, string $toAccount, float $amount): void
    {
        try {
            echo "+++ Processing Transfer: $amount +++ \n\n";

            $this->deductFromSender($fromAccount, $amount);
            $this->addToReceiver($toAccount, $amount);

            echo "=== Transaction completed ===\n\n";
        } catch (\Exception $e) {
            echo "\tError: " . $e->getMessage() . "\n";
            $this->rollbackTransaction();
            echo "=== Transaction Failed ===\n\n";
        }
    }

    private function deductFromSender(string $fromAccount, float $amount, string $type = "DEBIT"): void
    {
        echo "\tDeducting \$$amount From sender: $fromAccount\n";
        $this->executeTransaction($type, $fromAccount, $amount * -1);
    }

    private function addToReceiver(string $toAccount, float $amount, string $type = "CREDIT"): void
    {
        echo "\tCrediting \$$amount to recipient: $toAccount\n";
        $this->executeTransaction($type, $toAccount, $amount);
    }

    private function executeTransaction(string $type, string $accountName, float $amount): void
    {
        if (!isset($this->accounts[$accountName])) {
            throw new \Exception("Account $accountName does not exist.");
        }

        if ($type === "DEBIT" && $this->accounts[$accountName] + $amount < 0) {
            throw new \Exception("Declined $accountName.");
        }

        $this->accounts[$accountName] += $amount;
        $this->rollbackStack->push(["accountId" => $accountName, "amount" => -$amount]);

        $this->printAccounts();
    }

    private function rollbackTransaction(): void
    {
        echo "\t --- Rolling back transactions: ---\n";

        while (!$this->rollbackStack->isEmpty()) {
            $rollbackItem = $this->rollbackStack->getLastValue();
            $this->accounts[$rollbackItem["accountId"]] += $rollbackItem["amount"];
            echo "\tRollback: " . $rollbackItem["accountId"] . " \${$rollbackItem["amount"]}\n";
        }

        echo "\tBalance after Rollback:\n";
        $this->printAccounts();
    }

    public function printAccounts(): void
    {
        foreach ($this->accounts as $key => $val) {
            echo "\t" . $key . ": " . $val . "\n";
        }
        echo "\n";
    }
}