<?php

namespace Cookbook\Chapter10\LinkedList;

interface NodeInterface
{
    public function getNodeId(): string;

    public function getRoutine(): object;

    public function getNext(): ?NodeInterface;

    public function getPrevious(): ?NodeInterface;

    public function getPriority(): int;

    public function setNodeId(string $nodeId): NodeInterface;

    public function setRoutine(NodeInterface $routine): NodeInterface;

    public function setNext(NodeInterface $next): NodeInterface;

    public function setPrevious(NodeInterface $previous): NodeInterface;

    public function setPriority(int $priority): NodeInterface;
}