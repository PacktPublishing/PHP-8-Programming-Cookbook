<?php

namespace Cookbook\Chapter06\GenericInputForm\Factory;

use Cookbook\Chapter06\GenericInputForm\FormGeneratorInterface;
use Cookbook\Chapter06\GenericInputForm\GenericFormGenerator;

class FormFactory
{
    private FormGeneratorInterface $formGenerator;

    private array $formBuilders = [
        FormType::LOGIN->name => LoginFormBuilder::class,
        FormType::REGISTER->name => RegisterFormBuilder::class,
    ];

    public function __construct(FormGeneratorInterface $formGenerator = null)
    {
        $this->setFormGenerator($formGenerator);
    }

    public static function createInstance(): FormFactory
    {
        return new static();
    }

    public function create(FormType $type): GenericFormGenerator
    {
        if (!isset($this->formBuilders[$type->name])) {
            throw new \Exception('Form builder not found: ' . $type->name);
        }

        $builder = new $this->formBuilders[$type->name]();
        $builder->build($this->formGenerator);

        return $this->formGenerator;
    }

    public function getFormGenerator(): FormGeneratorInterface
    {
        return $this->formGenerator;
    }

    public function setFormGenerator(FormGeneratorInterface $formGenerator = null): FormFactory
    {
        if (!$formGenerator) {
            $this->formGenerator = new GenericFormGenerator();
        }
        return $this;
    }
}