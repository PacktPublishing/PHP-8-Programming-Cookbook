<?php
include __DIR__ . '/../vendor/autoload.php';
use Cookbook\Chapter01\HelloWorld;
$world = new HelloWorld();
echo $world->hello();
echo PHP_EOL;
