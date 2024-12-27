<?php

use Cookbook\Chapter06\Factory\FormFactory;

include __DIR__ . '/../../vendor/autoload.php';

$formFactory = new FormFactory();

$config = [
    'attributes' => ['action' => 'FormHandler/submit.php', 'method' => 'POST'],
    'elements' => [
        [
            'type' => 'text',
            'attributes' => ['name' => 'name_first', 'placeholder' => 'First Name'],
        ],
        [
            'type' => 'email',
            'attributes' => ['name' => 'email', 'placeholder' => 'Email'],
        ],
        [
            'type' => 'radio',
            'attributes' => ['name' => 'like-coffee'],
            'options' => ['no' => 'No', 'yes' => 'Yes'],
        ],
        [
            'type' => 'select',
            'attributes' => ['name' => 'checkin'],
            'options' => ['morning' => 'Morning', 'evening' => 'Evening'],
        ],
    ],
];

echo $formFactory->build($config);
