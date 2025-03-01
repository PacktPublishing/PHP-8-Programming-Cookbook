<?php

namespace Cookbook\Chapter06\FormHandler;

use Cookbook\Chapter06\Factory\InputType;

class Validation
{
    private array $rules = [];
    private array $filters = [];

    public function __construct()
    {
        $this->initializeFiltersAndRules();
    }

    public function process(array $postFields): array
    {
        $results = [];

        foreach ($postFields as $field) {
            $type = $field['type'] ?? null;
            $name = $field['name'] ?? null;
            $value = $field['value'] ?? null;

            if ($type && $name) {
                $filteredValue = $this->filter($type, $value);
                $isValid = $this->validate($type, $filteredValue);

                $results[$name] = [
                    'filteredValue' => $filteredValue,
                    'isValid' => $isValid
                ];
            }
        }

        return $results;
    }

    private function initializeFiltersAndRules(): void
    {
        $this->filters['text'] = [
            'trim'
        ];
        $this->rules['text'] = [
            fn($value) => !empty($value),
            fn($value) => ctype_alnum(str_replace(' ', '', $value))
        ];

        $this->filters['email'] = [
            'trim',
            'strtolower'
        ];
        $this->rules['email'] = [
            fn($value) => filter_var($value, FILTER_VALIDATE_EMAIL)
        ];

        $this->rules['radio'] = [
            fn($value) => !empty($value)
        ];

        $this->rules['select'] = [
            fn($value) => !empty($value)
        ];
    }

    private function filter(string $type, $value)
    {
        if (!isset($this->filters[$type])) {
            return $value;
        }

        foreach ($this->filters[$type] as $filter) {
            $value = $filter($value);
        }

        return $value;
    }

    private function validate(string $type, $value): bool
    {
        if (!isset($this->rules[$type])) {
            return true;
        }

        foreach ($this->rules[$type] as $rule) {
            if (!$rule($value)) {
                return false;
            }
        }

        return true;
    }

    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }
}