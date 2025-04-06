<?php

use Cookbook\Chapter06\InputHandler\User;
use Cookbook\Chapter06\InputHandler\Filter\FilterFactory;

include __DIR__ . '/../../vendor/autoload.php';

$firstName = "   Nirali   ";
$lastName = "  Zave  ";

$user = new User($firstName, $lastName);
$filter = FilterFactory::create($user->getFilterStack());
$filter->apply($user);

echo "========= \n";
echo "Before Filters: ";
echo 'First Name: ' . $firstName . "\n";
echo 'Last Name: ' . $lastName . "\n";
echo "First Name Length: " . strlen($firstName) . "\n";
echo "Last Name Length: " . strlen($lastName) . "\n";

echo "\n";
echo "After Filters:\n";
echo 'First Name: ' . $user->getFirstName() . "\n";
echo 'Last Name: ' . $user->getLastName() . "\n";
echo "First Name Length: " . strlen($user->getFirstName()) . "\n";
echo "Last Name Length: " . strlen($user->getLastName()) . "\n";
echo "========= \n";