<?php

namespace Cookbook\Chapter06\GenericInputForm\Factory;

use Cookbook\Chapter06\GenericInputForm\FormGeneratorInterface;

interface FormBuilderInterface
{
    public function build(FormGeneratorInterface $formGenerator): void;
}