<?php

use \Cookbook\Chapter06\FormHandler\Validation;

include __DIR__ . '/../../../vendor/autoload.php';

$postFields = [
    ['type' => 'text', 'name' => 'name_first', 'value' => $_POST['name_first'] ?? ''],
    ['type' => 'email', 'name' => 'email', 'value' => $_POST['email'] ?? ''],
    ['type' => 'radio', 'name' => 'like-coffee', 'value' => $_POST['like-coffee'] ?? ''],
    ['type' => 'select', 'name' => 'checkin', 'value' => $_POST['checkin'] ?? ''],
];

// Initialize Validation with applied defaults
$validation = new Validation();

$result = $validation->process($postFields);

echo json_encode($result);