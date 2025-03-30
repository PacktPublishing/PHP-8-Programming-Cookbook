<?php

use Cookbook\Chapter06\Radio\RadioGenerator;

include __DIR__ . '/../../vendor/autoload.php';

$radioGenerator = new RadioGenerator();

$freeCoffeeGroupOptions = [
    'fieldSetTitle' => 'Do you want a free cup of coffee?',
];

$freeCoffeeRadioElementsOptions = [
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

$whenToHaveFreeCoffeeGroupOptions = [
    'fieldSetTitle' => 'When do you want to have your free coffee?',
];

for ($hour = 0; $hour <= 23; $hour++) {
    $hourString = str_pad($hour, 2, '0', STR_PAD_LEFT) . ":00";
    $arrHourRadios[] = [
        'radio' => [
            'radioElementName' => 'when-free-coffee',
            'radioElementId' => 'when-coffee-hour-' . $hour,
            'radioElementValue' => $hour,
        ],
        'label' => [
            'labelFor' => 'when-coffee-hour-' . $hour,
            'labelText' => $hourString,
        ],
    ];
}
$whenToHaveFreeCoffeeRadioElementsOptions = $arrHourRadios;

echo $radioGenerator->generate($freeCoffeeGroupOptions, $freeCoffeeRadioElementsOptions);
echo $radioGenerator->generate($whenToHaveFreeCoffeeGroupOptions, $arrHourRadios);