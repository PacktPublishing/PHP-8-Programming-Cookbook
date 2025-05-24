<?php
$text = file(__DIR__ . '/data/war_and_peace.txt');
$num = 0;
$len = [];
foreach ($text as $line) {
    $line = trim($line);
    if (!empty($line)) {
        $len[] = strlen($line);
    }
    $num++;
}
echo 'Number Lines: ' . $num . PHP_EOL;
echo 'Average Len : ' . (array_sum($len) / count($len)) . PHP_EOL;

// Number Lines: 66035
// Average Len : 61.808192133712
