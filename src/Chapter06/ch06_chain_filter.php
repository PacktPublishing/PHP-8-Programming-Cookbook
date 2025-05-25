<?php

use Cookbook\Chapter06\InputHandler\User;
use Cookbook\Chapter06\InputHandler\Filter\FilterFactory;

include __DIR__ . '/../../vendor/autoload.php';

$firstName = "   Nirali   ";
$lastName = "  Zave  ";
$email = '  niralizave@email.com  ';
$password = 'abc123123';

$user = new User($firstName, $lastName, $email, $password);
$filter = FilterFactory::create($user->getFilterStack());
$filter->apply($user);

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