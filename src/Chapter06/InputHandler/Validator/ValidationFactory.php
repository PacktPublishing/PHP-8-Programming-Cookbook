<?php

namespace Cookbook\Chapter06\InputHandler\Validator;

class ValidationFactory
{
    public static function create(array $stack): ValidatorInterface
    {
        $validator = new BaseValidator();
        foreach ($stack as $className) {
            if (class_exists($className)) {
                $validator = new $className($validator);
            }
        }
        return $validator;
    }
}