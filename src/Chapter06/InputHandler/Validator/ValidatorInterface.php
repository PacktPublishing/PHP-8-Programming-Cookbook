<?php

namespace Cookbook\Chapter06\InputHandler\Validator;

interface ValidatorInterface
{
    public function validate(object $entity): array;
}