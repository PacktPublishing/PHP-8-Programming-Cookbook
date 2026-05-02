<?php
// TO DEMO: set up and run ch07_microservices_library.php to add entries to the api_call.log
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Middleware\GenAiUsageTracker;
// create GenAiUsageTracker instance accepting all defaults
$tracker = new GenAiUsageTracker();
$num = $tracker->updateCsv(eraseLog: FALSE, appendCsv: FALSE);
if (empty($num)) {
    echo 'No Updates' . PHP_EOL;
} else {
    echo 'Number of log entries added: ' . $num . PHP_EOL;
}
readfile(GenAiUsageTracker::CSV_FN);

