<?php

use Cookbook\Chapter06\Factory\FormFactory;

include __DIR__ . '/../../vendor/autoload.php';

$factory = new FormFactory();

$config = [
    'attributes' => [
        'method' => 'POST',
        'action' => '/submit',
        'id' => 'dynamic-form'
    ],
    'elements' => [

        // Radio
        [
            'type' => 'radio',
            'attributes' => [
                'name' => 'car',
                'id' => 'car'
            ],
            'options' => [
                'mercedes' => 'Mercedes',
                'toyota' => 'Toyota',
                'ford' => 'Ford',
            ]
        ],

        // Select
        [
            'type' => 'select',
            'attributes' => [
                'name' => 'car-year',
                'id' => 'car-year'
            ],
            'options' => [
                '2020' => '2020',
                '2021' => '2021',
                '2022' => '2022',
                '2023' => '2023',
                '2024' => '2024',
                '2025' => '2025',
                '2026' => '2026',
            ]
        ],
    ]
];

// Build the form, and child elements:
echo $factory->build($config);
