<?php

use Cookbook\Chapter06\InputHandler\User;
use Cookbook\Chapter06\InputHandler\Filter\FilterFactory;
use Cookbook\Chapter06\InputHandler\Validator\ValidationFactory;

include __DIR__ . '/../../vendor/autoload.php';

$firstName = "   Alexi5   ";
$lastName = "  Surfer5322 Very Long String Name Not Allowed  ";
$email = '  gru37@email.com  ';
$password = 'bac-123-182-WDj-sd';

$user = new User($firstName, $lastName, $email, $password);
$filter = FilterFactory::create($user->getFilterStack());
$filter->apply($user);

$validator = ValidationFactory::create($user->getValidationStack());
$errors = $validator->validate($user);

echo "========= \n";
echo "Before Filters: ";
echo 'First Name: ' . $firstName . "\n";
echo 'Last Name: ' . $lastName . "\n";
echo 'Email: ' . $email . "\n";
echo "First Name Length: " . strlen($firstName) . "\n";
echo "Last Name Length: " . strlen($lastName) . "\n";
echo "Email Length: " . strlen($email) . "\n";

echo "\n";
echo "After Filters:\n";
echo 'First Name: ' . $user->getFirstName() . "\n";
echo 'Last Name: ' . $user->getLastName() . "\n";
echo 'Last Name: ' . $user->getEmail() . "\n";
echo "First Name Length: " . strlen($user->getFirstName()) . "\n";
echo "Last Name Length: " . strlen($user->getLastName()) . "\n";
echo "Email Length: " . strlen($user->getEmail()) . "\n";
echo "========= \n";

echo "\nValidation Errors: \n";
print_r($errors);