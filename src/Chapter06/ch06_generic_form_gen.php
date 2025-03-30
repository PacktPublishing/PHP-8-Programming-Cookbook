<?php

use Cookbook\Chapter06\GenericInputForm\GenericFormGenerator;
use \Cookbook\Chapter06\GenericInputForm\InputType;

include __DIR__ . '/../../vendor/autoload.php';

// Instantiate generator, and add input fields.
$generator = new GenericFormGenerator();
$generator->addInput(InputType::Text, 'Username', 'username');
$generator->addInput(InputType::Password, 'Password', 'password');

// Echo the entire form.
echo $generator->generate('my-form', '');

// Output the username, after submission.
if (isset($_POST['submit'])) {
    echo "Hello " . $_POST['username'] . "<br />";
    print_r($_POST);
}