<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\REST\SimpleAPICall;
use Cookbook\REST\EnumMethod;
use Cookbook\REST\EnumExt;
$data = [
    'postcode' => strip_tags($argv[1] ?? $_GET['postcode'] ?? 13676),
    'country'  => strip_tags($argv[2] ?? $_GET['country'] ?? 'FR')
];
$display = fn (float $start) => sprintf("\nElapsed time: %.4f\n", (microtime(TRUE) - $start));


// HTTP GET request using Streams
$start = microtime(TRUE);
echo SimpleAPICall::send($data, EnumMethod::GET, EnumExt::STREAMS);
echo $display($start);

// HTTP GET request using cURL
$start = microtime(TRUE);
echo SimpleAPICall::send($data, EnumMethod::GET, EnumExt::CURL);
echo $display($start);

// HTTP GET request using Streams
$start = microtime(TRUE);
echo SimpleAPICall::send($data, EnumMethod::POST, EnumExt::STREAMS);
echo $display($start);

// HTTP GET request using cURL
$start = microtime(TRUE);
echo SimpleAPICall::send($data, EnumMethod::POST, EnumExt::CURL);
echo $display($start);
