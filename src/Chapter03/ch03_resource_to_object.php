<?php
include __DIR__ . '/ch03_resource_to_object_func_lib.php';

// file resource
$fn  = __DIR__ . '/../../data/war_and_peace.txt';
$fh  = fopen($fn, 'r');

// curl resource
$url = 'https://unlikelysource.com';
$ch  = curl_init($url);

// Test $fh using conventional approach
echo test_conventional($fh, basename($fn));

// Test $ch using conventional approach
echo test_conventional($ch, $url);

// Test $fh using updated approach
echo test_updated($fh, basename($fn));

// Test $ch using updated approach
echo test_updated($ch, $url);

// actual output:
/*
war_and_peace.txt was successfully opened.
$resource is this type of resource: stream
Unable to open https://unlikelysource.com
war_and_peace.txt was successfully opened.
$resource is this type of resource: resource
https://unlikelysource.com was successfully opened.
$resource is this type of resource: object
*/
