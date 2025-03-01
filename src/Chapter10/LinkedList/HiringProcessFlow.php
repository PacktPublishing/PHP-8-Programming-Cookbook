<?php

namespace Cookbook\Chapter10\LinkedList;

class HiringProcessFlow implements QueueInterface
{
    private ?NodeInterface $head;
    private ?NodeInterface $tail;

    public function __construct() {
        $this->head = null;
        $this->tail = null;
    }

    // Add a new process to the queue
    public function addNode(NodeInterface $node): void {
        if ($this->tail === null) {
            $this->head = $this->tail = $node;
        } else {
            $this->tail->setNext($node);
            $node->setPrevious($this->tail);
            $this->tail = $node;
        }
    }

    // Execute the processes in order
    public function execute(): array
    {
        $executedProcesses = [];

        if ($this->head === null) {
            return [];
        }

        $current = $this->head;
        while ($current !== null) {
            $executedProcesses[] = "Process #{$current->getNodeId()} | " . $current->getRoutine()();
            $current = $current->getNext();
        }

        // Reset after execution
        $this->head = $this->tail = null;

        return $executedProcesses;
    }

    // Get list of pending processes
    public function getPendingProcesses(): array {
        $pendingProcesses = [];

        if ($this->head === null) {
            return ["No pending processes."];
        }

        $current = $this->head;
        while ($current !== null) {
            $pendingProcesses[] = "Process #{$current->getNodeId()} | " . $current->getRoutine()();
            $current = $current->getNext();
        }

        return $pendingProcesses;
    }
}
