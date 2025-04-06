<?php

namespace Cookbook\Chapter06\InputHandler\Validator;

class RequiredFieldValidator implements ValidatorInterface
{
    public function __construct(private ValidatorInterface $next)
    {
    }

    public function validate(object $entity): array
    {
        $errors = $this->next->validate($entity);
        $rules = $entity->getValidationRules();

        foreach ($rules as $field => $meta) {
            if (!($meta['required'] ?? false)) {
                continue;
            }
            $getter = $meta['getter'];
            $value = trim((string)$entity->{$getter}());
            if ($value === '') {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
            }
        }

        return $errors;
    }
}