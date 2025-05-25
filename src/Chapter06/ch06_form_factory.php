<?php

use \Cookbook\Chapter06\GenericInputForm\Factory\FormFactory;
use \Cookbook\Chapter06\GenericInputForm\Factory\FormType;

include __DIR__ . '/../../vendor/autoload.php';

echo "<h1>Login</h1>\n";
echo FormFactory::createInstance()->create(FormType::LOGIN)->generate('login-form', '/login');

echo "<h1>Register</h1>\n";
echo FormFactory::createInstance()->create(FormType::REGISTER)->generate('registration-form', '/register');

// Add styling
echo "<style>body {font-family: arial,serif; } input { width: 200px; } label {float:left; width:100px; }</style>";