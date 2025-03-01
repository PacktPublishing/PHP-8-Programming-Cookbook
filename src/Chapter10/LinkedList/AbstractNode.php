<?php

namespace Cookbook\Chapter10\LinkedList;

abstract class AbstractNode implements NodeInterface
{
    private string $nodeId;
    private object $routine;
    private ?NodeInterface $next = null;
    private ?NodeInterface $previous = null;
    private string $nodeType;
    private int $priority;

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function setNodeId(string $nodeId): NodeInterface
    {
        $this->nodeId = $nodeId;
        return $this;
    }

    public function getRoutine(): object
    {
        return $this->routine;
    }

    public function setRoutine(object $routine): NodeInterface
    {
        $this->routine = $routine;
        return $this;
    }

    public function getNext(): ?NodeInterface
    {
        return $this->next;
    }

    public function setNext(NodeInterface $next): NodeInterface
    {
        $this->next = $next;
        return $this;
    }

    public function getPrevious(): ?NodeInterface
    {
        return $this->previous;
    }

    public function setPrevious(NodeInterface $previous): NodeInterface
    {
        $this->previous = $previous;
        return $this;
    }

    public function getNodeType(): string
    {
        return $this->nodeType;
    }

    public function setNodeType(string $nodeType): NodeInterface
    {
        $this->nodeType = $nodeType;
        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): AbstractNode
    {
        $this->priority = $priority;
        return $this;
    }
}