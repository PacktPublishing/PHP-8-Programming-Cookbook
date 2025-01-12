<?php

use Cookbook\Chapter08\Cleaner\InputCleaner;

include __DIR__ . '/../../vendor/autoload.php';

$inputCleaner = new InputCleaner();

$userInput = "&#x1F600; <script>alert('hi');</script>";
$sanitizedInput = $inputCleaner->clean($userInput);

// Do Database persistence code here:
    // DB-specific code.

// Output
echo $sanitizedInput;