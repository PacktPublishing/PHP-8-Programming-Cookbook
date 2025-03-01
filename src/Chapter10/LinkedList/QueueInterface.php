<?php

namespace Cookbook\Chapter10\LinkedList;

interface QueueInterface
{
    public function addNode(NodeInterface $node): void;

    public function execute(): array;
}