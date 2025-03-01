<?php

namespace Cookbook\Chapter10\Stack;

class Stack
{
    private array $stack = [];

    public function push(mixed $item): void
    {
        $this->stack[] = $item;
    }

    public function getLastValue(): mixed
    {
        return empty($this->stack) ? null : array_pop($this->stack);
    }

    public function isEmpty(): bool
    {
        return empty($this->stack);
    }
}