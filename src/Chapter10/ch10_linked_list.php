<?php

use Cookbook\Chapter10\LinkedList\HiringNode;
use Cookbook\Chapter10\LinkedList\HiringProcessFlow;

include __DIR__ . '/../../vendor/autoload.php';

// Example Usage
$processQueue = new HiringProcessFlow();

// Process Item 1
$process1 = new HiringNode();
$process1->setNodeId(rand(1, 999));
$closure1 = fn() => ("Get budget approval from Yaron");
$process1->setRoutine($closure1);
$processQueue->addNode($process1);

// Process Item 2
$process2 = new HiringNode();
$process2->setNodeId(rand(1, 999));
$closure2 = fn() => ("Rain conducts interviews");
$process2->setRoutine($closure2);
$processQueue->addNode($process2);

// Process Item 3
$process3 = new HiringNode();
$process3->setNodeId(rand(1, 999));
$closure3 = fn() => ("Alexis submits for approval");
$process3->setRoutine($closure3);
$processQueue->addNode($process3);

// Process Item 4
$process4 = new HiringNode();
$process4->setNodeId(rand(1, 999));
$closure4 = fn() => ("Yaron approves");
$process4->setRoutine($closure4);
$processQueue->addNode($process4);

echo "\n\n***********************************\n";
echo "*        PENDING PROCESSES        *\n";
echo "***********************************\n";
foreach ($processQueue->getPendingProcesses() as $process) {
    echo "[PENDING] $process\n";
}
echo "***********************************\n";

echo "\n\n###################################\n";
echo "#          EXECUTING TASKS        #\n";
echo "###################################\n";
foreach ($processQueue->execute() as $executedProcess) {
    echo "[EXECUTING] $executedProcess\n";
}
echo "\n\n###################################\n";
echo "[COMPLETED] All processes executed.\n";
echo "###################################\n";

echo "\n\n***********************************\n";
echo "*        PENDING PROCESSES        *\n";
echo "***********************************\n";
foreach ($processQueue->getPendingProcesses() as $process) {
    echo "[PENDING] $process\n";
}
echo "***********************************\n";
