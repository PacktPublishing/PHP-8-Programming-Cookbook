<?php
require __DIR__ . '/../../vendor/autoload.php';
$fn = __DIR__ . '/../../data/war_and_peace.txt';
try {
    $largeFile = new Cookbook\Iterator\LargeFile($fn);
    $iterator = $largeFile->getIterator('ByLine');
    $words = 0;
    foreach ($iterator as $line) {
        echo $line;
        $words += str_word_count($line);
    }
    echo str_repeat('-', 52) . PHP_EOL;
    printf("%-40s : %8d\n", 'Total Words', $words);
    printf("%-40s : %8d\n", 'Average Words Per Line', ($words / $iterator->getReturn()));
    echo str_repeat('-', 52) . PHP_EOL;
} catch (Throwable $e) {
echo $e->getMessage();
}
