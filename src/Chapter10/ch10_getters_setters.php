<?php

use Cookbook\Chapter10\GettersSetters\Person;
use Cookbook\Chapter10\GettersSetters\Address;

include __DIR__ . '/../../vendor/autoload.php';

// Dynamic function to echo properties based on getter methods.
function echoProperties(object $object, $indent = ''): void
{
    // Get all public methods of the object.
    $methods = get_class_methods($object);

    // Filter methods that start with 'get'
    foreach ($methods as $method) {
        if (str_starts_with($method, 'get')) {
            // Determine property name from getter, e.g., getFirstName -> firstName
            $property = lcfirst(substr($method, 3));
            $value = $object->$method();

            if (is_object($value)) {
                echo "{$indent}" . ucfirst($property) . ": [Object]\n";
                // Recursively echo properties of the nested object.
                echoProperties($value, $indent . "  ");
            } else {
                echo "{$indent}" . ucfirst($property) . ": " . $value . "\n";
            }
        }
    }
}

// Create an instance of the Address class.
$address = new Address();
$address->setState('VIC');
$address->setPostcode('3006');
$address->setSuburb('Southbank');
$address->setStreet('Fawkner');

// Create an instance of the Person class, and set the Address we just created.
$person = new Person();
$person->setFirstName('Alexis');
$person->setLastName('Gruet');
$person->setAge(31);
$person->setAddress($address); // Set the Address object to the Person.

echo "Person Properties:\n";
echoProperties($person);

