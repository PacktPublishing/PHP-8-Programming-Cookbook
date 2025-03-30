<?php

use Cookbook\Chapter06\GenericInputForm\GenericFormGenerator;
use \Cookbook\Chapter06\GenericInputForm\InputType;

include __DIR__ . '/../../vendor/autoload.php';

// Instantiate generator, and add input fields.
$generator = new GenericFormGenerator();
$generator->addInput(InputType::Text, ['label' => 'Username', 'name' => 'username']);
$generator->addInput(InputType::Password, ['label' => 'Password', 'name' => 'password']);

// Echo the entire form.
echo $generator->generate('my-form', '');

// Add styling
echo "<style>input { width: 200px; } label {float:left; width:100px; }</style>";

// Output Array.
print_r($_POST);