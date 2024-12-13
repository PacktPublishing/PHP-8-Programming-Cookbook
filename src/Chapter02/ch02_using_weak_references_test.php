<?php
require __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Database\{PostCode, Connect};
$config = require __DIR__ . '/../../config/db.config.php';
$city   = trim(strip_tags($_GET['city'] ?? $argv[1] ?? ''));
if (empty($city)) {
    echo 'USAGE: php ' . basename(__FILE__) . ' CITY' . PHP_EOL;
    exit;
}


// get a single city
$post = new PostCode(Connect::getConnection($config['ch02']));
$obj  = $post->findOneCity($city);

// assign object to storage
$storage = new SplObjectStorage();
$storage->attach($obj);

// show results
echo 'Dumping storage' . PHP_EOL;
print_r($storage);
echo 'Retrieving object' . PHP_EOL;
$new = $storage->current();
echo $new->place_name . PHP_EOL;
echo 'Unsetting object' . PHP_EOL;
unset($obj, $new, $post);
echo 'Dumping storage after object is unset' . PHP_EOL;
echo $new->place_name . PHP_EOL;
print_r($storage);  // object still exists!
echo 'Peak memory: ' . memory_get_peak_usage() . PHP_EOL;
memory_reset_peak_usage();

// get a single city
$post = new PostCode(Connect::getConnection($config['ch02']));
$obj  = $post->findOneCity($city);

// assign object to storage
$storage = new class() extends SplObjectStorage {
    public function current()
    {
        return parent::current()->get();
    }
    public function key()
    {
        return parent::key()->get();
    }
};
$storage->attach(WeakReference::create($obj));

// show results
echo 'Dumping storage' . PHP_EOL;
print_r($storage);
echo 'Retrieving object' . PHP_EOL;
$new = $storage->current();
echo $new->place_name . PHP_EOL;
echo 'Unsetting object' . PHP_EOL;
unset($obj, $new);
echo 'Dumping storage after object is unset' . PHP_EOL;
echo $new->place_name . PHP_EOL;
print_r($storage);  // object still exists!
echo 'Peak memory: ' . memory_get_peak_usage() . PHP_EOL;
memory_reset_peak_usage();
