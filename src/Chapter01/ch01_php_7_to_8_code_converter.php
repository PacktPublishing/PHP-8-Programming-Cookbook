<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Chapter01\Converter\Convert;
$fn[] = __DIR__ . '/test_file/test_seven.php';
$fn[] = __DIR__ . '/test_file/Test/Seven.php';
$convert = new Convert(include __DIR__ . '/config/config.php');
foreach ($fn as $item) {
	echo "\nConverting: $item\n";
	echo "****************************************\n";
	echo $convert->convert($item);
}
