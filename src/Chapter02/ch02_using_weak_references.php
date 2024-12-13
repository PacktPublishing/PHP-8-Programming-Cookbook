<?php
require __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Database\{PostCode, Connect};
$config = require __DIR__ . '/../../config/db.config.php';
$city   = trim(strip_tags($_GET['city'] ?? $argv[1] ?? ''));
if (empty($city)) {
    echo 'USAGE: php ' . basename(__FILE__) . ' CITY' . PHP_EOL;
    exit;
}

echo 'Normal Object Storage' . PHP_EOL;
$total = 0;
$found = 0;
$post   = new PostCode(Connect::getConnection($config['ch02']));
$storage = $post->findCity($city);
unset($post);
foreach ($storage as $obj) {
    $total++;
    if (trim($obj->place_name) !== $city) {
        printf("%40s : UNSET\n", $obj->place_name);
        unset($obj);
    } else {
        $found++;
        printf("%40s : %2s : %10s\n", $obj->place_name, $obj->admin_code1, $obj->postal_code);
    }
}
echo 'Total items containing search key       : ' . $total . PHP_EOL;
echo 'Total items exactly matching search key : ' . $found . PHP_EOL;
echo 'Memory Usage: ' . memory_get_peak_usage() . PHP_EOL;

$storage->rewind();
$total = 0;
foreach ($storage as $obj) {
    $total++;
    printf("%40s : %2s : %10s\n", $obj->place_name, $obj->admin_code1, $obj->postal_code);
}
echo 'Total items containing search key       : ' . $total . PHP_EOL;
echo 'TO NOTICE: even though the objects were unset ... they still exist because of the back-reference!' . PHP_EOL;
memory_reset_peak_usage();

echo 'Weak Ref Object Storage' . PHP_EOL;
$total = 0;
$found = 0;
$post   = new PostCode(Connect::getConnection($config['ch02']));
$storage = $post->findCity($city);
unset($post);
foreach ($storage as $obj => $idx) {
    $total++;
    if (trim($obj->place_name) !== $city) {
        printf("%40s : UNSET\n", $obj->place_name);
        unset($obj);
    } else {
        $found++;
        printf("%40s : %2s : %10s\n", $obj->place_name, $obj->admin_code1, $obj->postal_code);
    }
}
echo 'Total items containing search key       : ' . $total . PHP_EOL;
echo 'Total items exactly matching search key : ' . $found . PHP_EOL;
echo 'Memory Usage: ' . memory_get_peak_usage() . PHP_EOL;

$storage->rewind();
$total = 0;
foreach ($storage as $obj => $idx) {
    $total++;
    if (!empty($obj) && is_object($obj)) {
        printf("%40s : %2s : %10s\n", $obj->place_name, $obj->admin_code1, $obj->postal_code);
    } else {
        echo 'Object does not exist' . PHP_EOL;
    }
}
echo 'Total items containing search key       : ' . $total . PHP_EOL;
echo 'TO NOTICE: even though the objects were unset ... they still exist because of the back-reference!' . PHP_EOL;
