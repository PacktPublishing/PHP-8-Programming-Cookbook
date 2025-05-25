<?php

namespace Cookbook\Chapter06\InputHandler\Validator;

class BaseValidator implements ValidatorInterface
{
    public function validate(object $entity): array
    {
        return [];
    }
}