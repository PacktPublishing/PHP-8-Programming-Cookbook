<?php

use Cookbook\Chapter10\GettersSetters\Person;
use Cookbook\Chapter10\GettersSetters\Address;

include __DIR__ . '/../../vendor/autoload.php';

// Handle exceptions if invalid inputs are set.
function safeSet(object $object, string $setter, $value): void
{
    try {
        $object->$setter($value);
    } catch (Exception $e) {
        echo "Error: Invalid input provided." . $e->getMessage() . "\n";
    }
}

function echoProperties(object $object, $indent = ''): void
{
    $methods = get_class_methods($object);
    foreach ($methods as $method) {
        if (str_starts_with($method, 'get')) {
            $property = lcfirst(substr($method, 3));
            $value = $object->$method();
            if (is_object($value)) {
                echo "{$indent}" . ucfirst($property) . ": [Object]\n";
                echoProperties($value, $indent . "  ");
            } else {
                echo "{$indent}" . ucfirst($property) . ": " . $value . "\n";
            }
        }
    }
}

echo "Enter Address Details:\n";
$street = readline("Street: ");
$suburb = readline("Suburb: ");
$state = readline("State (NSW, VIC, QLD, WA, SA, TAS, ACT, NT): ");
$postcode = readline("Postcode (4 digits): ");

$address = new Address();
safeSet($address, 'setStreet', $street);
safeSet($address, 'setSuburb', $suburb);
safeSet($address, 'setState', $state);
safeSet($address, 'setPostcode', $postcode);

echo "\nEnter Person Details:\n";
$firstName = readline("First Name: ");
$lastName = readline("Last Name: ");
$age = readline("Age: ");

$person = new Person();
safeSet($person, 'setFirstName', $firstName);
safeSet($person, 'setLastName', $lastName);
safeSet($person, 'setAge', (int)$age);
safeSet($person, 'setAddress', $address);

echo "\nPerson Properties:\n";
echoProperties($person);

