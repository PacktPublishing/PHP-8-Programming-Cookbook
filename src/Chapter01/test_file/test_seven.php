<?php
define('BASE_DIR', __DIR__, TRUE);
function __autoload($class)
{
	$fn = __DIR__ . str_replace('\\', '/', $class) . '.php';
	require ($fn);
}
use Test\Seven;
$test = new Seven(['A' => 111, 'B' => 222, 'C' => 333]);
echo $test->getConf('A');
echo PHP_EOL;
echo $test->add(5.55, 6.66);
var_dump((unset) $test);
