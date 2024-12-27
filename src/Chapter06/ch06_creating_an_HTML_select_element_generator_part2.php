<?php

use Cookbook\Chapter06\Select\SelectGenerator;

include __DIR__ . '/../../vendor/autoload.php';

$selectOptions = [
    'selectName' => 'select-when-free-coffee',
    'selectId' => 'select-when-free-coffee'
];

$elementOptions = [];
for ($i = 0; $i < 24; $i++) {
    $hour = str_pad($i, 2, "0", STR_PAD_LEFT) . ":00";
    $elementOptions[] = ['option' => ['optionValue' => (string)$i, 'optionText' => $hour]];
}

$generator = new SelectGenerator();

echo '<label for="select-when-free-coffee" style="display: block;">When do you want to have your free coffee?</label>';
echo $generator->generate($selectOptions, $elementOptions);