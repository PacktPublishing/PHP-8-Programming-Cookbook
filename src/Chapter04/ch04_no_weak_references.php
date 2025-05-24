<?php
define('LINES_TO_SKIP', 100); // skip 100 lines between
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Geonames\PostCode;
use Cookbook\Iterator\LargeFile;
$fn = __DIR__ . '/../../data/US.txt';
$iterator = (new LargeFile($fn))->getIterator(); // yields one line at a time

// using SplObjectStorage
echo '*************** Using SplObjectStorage *******************' . PHP_EOL;
$start = microtime(TRUE);
$storage = [];
// get header row
$headers = $iterator->current();
while ($iterator->valid()) {
    try {
        $item = $iterator->current();
        $row = str_getcsv($item, "\t");
        if (empty($row[1])) {
            $iterator->next();
            continue;
        }
        $storage[$row[1]] = new PostCode($row);
    } catch (Throwable $e) {
        // do nothing
    }
    // skip lines
    for ($x = 0; $x < LINES_TO_SKIP; $x++) {
        if (!$iterator->valid()) break;
        $iterator->next();
    }
}
// display results
printf("%20s | %10s | %4s\n", 'City', 'State/Prov', 'ISO2');
printf("%20s | %10s | %4s\n", '--------------------', '----------', '----');
foreach ($storage as $key => $obj) {
    printf( "%20s | %10s | %4s\n", 
            $obj->getCityName(), 
            $obj->getStateProvCode(), 
            $obj->getCountry());
    unset($storage[$key]);
}
gc_collect_cycles();
echo 'Elapsed Time: ' . (microtime(TRUE) - $start) . PHP_EOL;
echo 'Memory Used : ' . number_format(memory_get_usage()) . PHP_EOL;
/*
Elapsed Time: 0.016633987426758
Memory Used : 1,184,440
*/
