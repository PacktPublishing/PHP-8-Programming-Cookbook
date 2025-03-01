<?php

namespace Cookbook\Chapter10\GettersSetters;

use Cookbook\Chapter10\GettersSetters\Address;

class Person
{
    // Private Property Declarations
    private string $firstName;
    private string $lastName;
    private int $age;
    private Address $address;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        // Custom logic in the mutator.
        if ($this->validateName($firstName)) {
            $this->firstName = $firstName;
        }
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        // Custom logic in the mutator.
        if ($this->validateName($lastName)) {
            $this->lastName = $lastName;
        }
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        // Custom logic in the mutator.
        if ($this->validateAge($age)) {
            $this->age = $age;
        }
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    private function validateName(string $name): bool
    {
        if (trim($name) === '') {
            throw new \Exception("Name string cannot be empty.");
        }

        if (strlen($name) < 2) {
            throw new \Exception("Name should be at least 2 characters.");
        }

        return true;
    }

    private function validateAge(int $age): bool
    {
        if ($age < 0) {
            throw new \Exception("Age cannot be a negative value.");
        }

        return true;
    }
}