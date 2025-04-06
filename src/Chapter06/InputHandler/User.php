<?php

namespace Cookbook\Chapter06\InputHandler;

use Cookbook\Chapter06\InputHandler\Filter\TrimFilter;
use Cookbook\Chapter06\InputHandler\Filter\UpperCaseFilter;

class User
{
    private string $first_name;
    private string $last_name;

    public function __construct(string $first_name, string $last_name)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
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
        ];
    }

    public function getValidationStack(): array
    {
        return [
            'NoNumericValidator',
            'LengthValidator',
            'RequiredFieldValidator',
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
