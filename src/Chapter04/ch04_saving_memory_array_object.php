<?php
define('LINES_TO_SKIP', 100); // skip 100 lines between
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Iterator\LargeFile;
use Cookbook\Geonames\{PostCode, PostCodeView};
$fn = __DIR__ . '/../../data/US.txt';
$iterator = (new LargeFile($fn))->getIterator(); // yields one line at a time

// display results
function display (iterable $storage)
{
    printf("%20s | %10s | %4s\n", 'City', 'State/Prov', 'Post Code');
    printf("%20s | %10s | %4s\n", '--------------------', '----------', '----');
    foreach ($storage as $key => $obj) {
        printf( "%20s | %10s | %4s\n", 
                $obj->getCityName(),
                $obj->getStateProvCode(),
                $key);
    }
}

// build the array
$start = microtime(TRUE);
$storage = new ArrayObject();
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
$view = new PostCodeView($storage);
$view->showAsObj();

// metrics
echo 'Elapsed Time: ' . (microtime(TRUE) - $start) . PHP_EOL;
echo 'Memory Usage: ' . number_format(memory_get_usage()) . PHP_EOL;
echo 'Peak Memory : ' . number_format(memory_get_peak_usage()) . PHP_EOL;

/*
Elapsed Time: 0.013475894927979
Memory Usage: 1,699,568
Peak Memory : 1,740,824
*/
