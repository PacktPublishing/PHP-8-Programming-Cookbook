<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\{Container,Ipsum};
$fn2 = __DIR__ . '/../../data/complete_works_of_william_shakespeare.txt';
$container = Container::getInstance();

$ipsum1 = new Ipsum();
echo $ipsum1(6);

$ipsum2 = new Ipsum($fn2);
echo $ipsum2(6);
