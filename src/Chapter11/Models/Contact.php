<?php

namespace Cookbook\Chapter11\Models;

class Contact
{
    private string $name;
    private string $email;
    private string $phone;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Contact
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): Contact
    {
        $this->phone = $phone;
        return $this;
    }
}