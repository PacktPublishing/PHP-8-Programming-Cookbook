<?php

namespace Cookbook\Chapter06\InputHandler\Validator;

class LengthValidator implements ValidatorInterface
{
    public function __construct(private ValidatorInterface $next)
    {
    }

    public function validate(object $entity): array
    {
        $errors = $this->next->validate($entity);
        $rules = $entity->getValidationRules();

        foreach ($rules as $field => $meta) {
            if (!isset($meta['max_length'])) {
                continue;
            }
            $getter = $meta['getter'];
            $value = (string)$entity->{$getter}();
            if (strlen($value) > $meta['max_length']) {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) .
                    " must not exceed {$meta['max_length']} characters.";
            }
        }

        return $errors;
    }
}