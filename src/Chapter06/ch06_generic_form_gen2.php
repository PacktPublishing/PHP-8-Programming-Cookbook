<?php

use Cookbook\Chapter06\GenericInputForm\GenericFormGenerator;
use \Cookbook\Chapter06\GenericInputForm\InputType;

include __DIR__ . '/../../vendor/autoload.php';

// Instantiate generator, and add input fields.
$generator = new GenericFormGenerator();
$generator->addInput(InputType::Email, ['label' => 'Email', 'name' => 'email']);
$generator->addInput(InputType::Text, ['label' => 'Street Address', 'name' => 'street1']);
$generator->addInput(InputType::Text, ['label' => 'Suburb', 'name' => 'suburb']);
$generator->addInput(InputType::Text, ['label' => 'State', 'name' => 'state']);
$generator->addInput(InputType::Text, ['label' => 'Postal Code', 'name' => 'postal_code']);

// Echo the entire form.
echo $generator->generate('my-form', '');// Add styling

// Add styling
echo "<style>input { width: 200px; } label {float:left; width:150px; }</style>";

// Output Array.
print_r($_POST);