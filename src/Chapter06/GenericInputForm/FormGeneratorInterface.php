<?php

namespace Cookbook\Chapter06\GenericInputForm;

interface FormGeneratorInterface
{
    public function generate(string $formName, string $submitUrl, array $options = []);

    public function addInput(InputType $inputType, array $options = []);
}