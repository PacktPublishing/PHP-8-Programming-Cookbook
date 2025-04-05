<?php

use Cookbook\Chapter06\GenericInputForm\GenericFormGenerator;
use \Cookbook\Chapter06\GenericInputForm\InputType;

include __DIR__ . '/../../vendor/autoload.php';

// Radio Options:
$groupOptions = [
    'fieldSetTitle' => 'Do you want a free cup of coffee?',
];

$elementOptions = [
    [
        'radio' => [
            'radioElementName' => 'free-coffee',
            'radioElementId' => 'coffee-yes',
            'radioElementValue' => 'yes',
        ],
        'label' => [
            'labelFor' => 'coffee-yes',
            'labelText' => 'Yes',
        ],
    ],

    [
        'radio' => [
            'radioElementName' => 'free-coffee',
            'radioElementId' => 'coffee-no',
            'radioElementValue' => 'no',
        ],
        'label' => [
            'labelFor' => 'coffee-no',
            'labelText' => 'No',
        ],
    ],
];

// Instantiate generator, and add input fields.
$generator = new GenericFormGenerator();
$generator->addInput(
    InputType::Radio,
    ['radio_group_options' => $groupOptions, 'radio_element_options' => $elementOptions]
);

// Echo the entire form.
echo $generator->generate('my-radio-form', '');
