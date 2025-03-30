<?php

use Cookbook\Chapter06\GenericInputForm\GenericFormGenerator;
use \Cookbook\Chapter06\GenericInputForm\InputType;

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

// Instantiate generator, and add input fields.
$generator = new GenericFormGenerator();
$generator->addInput(InputType::Select,
    ['select_options' => $selectOptions, 'select_element_options' => $elementOptions]);

// Echo the entire form.
echo $generator->generate('my-radio-form', '');
