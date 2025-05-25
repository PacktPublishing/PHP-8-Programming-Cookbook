<?php

namespace Cookbook\Chapter06\InputHandler\Validator;

class NoNumericValidator implements ValidatorInterface
{
    public function __construct(private ValidatorInterface $next)
    {
    }

    public function validate(object $entity): array
    {
        $errors = $this->next->validate($entity);
        $rules = $entity->getValidationRules();

        foreach ($rules as $field => $meta) {
            if (!($meta['no_numeric'] ?? false)) {
                continue;
            }
            $getter = $meta['getter'];
            $value = (string)$entity->{$getter}();
            if (preg_match('/\\d/', $value)) {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must not contain numbers.';
            }
        }

        return $errors;
    }
}