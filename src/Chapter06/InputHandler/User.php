<?php

namespace Cookbook\Chapter06\InputHandler;

use Cookbook\Chapter06\InputHandler\Filter\TrimFilter;
use Cookbook\Chapter06\InputHandler\Filter\UpperCaseFilter;
use Cookbook\Chapter06\InputHandler\Validator\LengthValidator;
use Cookbook\Chapter06\InputHandler\Validator\NoNumericValidator;
use Cookbook\Chapter06\InputHandler\Validator\RequiredFieldValidator;

class User
{
    private string $first_name;
    private string $last_name;
    private string $email;
    private string $password;

    public function __construct(string $first_name, string $last_name, string $email, string $password)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $v): void
    {
        $this->first_name = $v;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $v): void
    {
        $this->last_name = $v;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $v): void
    {
        $this->email = $v;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $v): void
    {
        $this->password = $v;
    }

    public function getValidationRules(): array
    {
        return [
            'first_name' => [
                'getter' => 'getFirstName',
                'setter' => 'setFirstName',
                'required' => true,
                'max_length' => 25,
                'no_numeric' => true,
            ],
            'last_name' => [
                'getter' => 'getLastName',
                'setter' => 'setLastName',
                'required' => true,
                'max_length' => 30,
                'no_numeric' => true,
            ],
            'email' => [
                'getter' => 'getEmail',
                'setter' => 'setEmail',
                'required' => true,
                'email' => true,
            ],
            'password' => [
                'getter' => 'getPassword',
                'setter' => 'setPassword',
                'required' => true,
                'min_length' => 8,
                'max_length' => 64,
            ]
        ];
    }

    public function getFilterRules(): array
    {
        return [
            'first_name' => [
                'getter' => 'getFirstName',
                'setter' => 'setFirstName',
                'trim' => true,
                'uppercase' => true,
            ],
            'last_name' => [
                'getter' => 'getLastName',
                'setter' => 'setLastName',
                'trim' => true,
                'uppercase' => true,
            ],
            'email' => [
                'getter' => 'getEmail',
                'setter' => 'setEmail',
                'trim' => true,
            ]
        ];
    }

    public function getValidationStack(): array
    {
        return [
            NoNumericValidator::class,
            LengthValidator::class,
            RequiredFieldValidator::class,
        ];
    }

    public function getFilterStack(): array
    {
        return [
            TrimFilter::class,
            UpperCaseFilter::class,
        ];
    }
}