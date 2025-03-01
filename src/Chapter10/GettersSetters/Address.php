<?php

namespace Cookbook\Chapter10\GettersSetters;

class Address
{
    // Private properties to enforce encapsulation
    private string $street;
    private string $suburb;
    private string $state;
    private string $postcode;
    private string $country = "Australia";

    // Australian states and territories
    private static array $validStates = ["NSW", "VIC", "QLD", "WA", "SA", "TAS", "ACT", "NT"];

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        if (trim($street) === '') {
            throw new \Exception("Street cannot be empty.");
        }
        $this->street = $street;
    }

    public function getSuburb(): string
    {
        return $this->suburb;
    }

    public function setSuburb(string $suburb): void
    {
        if (trim($suburb) === '') {
            throw new \Exception("Suburb cannot be empty.");
        }
        $this->suburb = $suburb;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $state = strtoupper(trim($state));
        if (!in_array($state, self::$validStates)) {
            throw new \Exception(
                "Invalid Australian state or territory. Allowed values: " . implode(', ', self::$validStates)
            );
        }
        $this->state = $state;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    // Setter for the postcode property with validation for a 4-digit postcode
    public function setPostcode(string $postcode): void
    {
        $postcode = trim($postcode);
        if (!preg_match('/^\d{4}$/', $postcode)) {
            throw new \Exception("Postcode must be a 4-digit number.");
        }
        $this->postcode = $postcode;
    }

    // Getter for the country property
    public function getCountry(): string
    {
        return $this->country;
    }

    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        trigger_error("Property Does Not Exist: " . __CLASS__ . "::$name", E_USER_NOTICE);
        return null;
    }
}