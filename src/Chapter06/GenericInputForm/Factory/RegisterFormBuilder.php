<?php

namespace Cookbook\Chapter06\GenericInputForm\Factory;

use Cookbook\Chapter06\GenericInputForm\FormGeneratorInterface;
use Cookbook\Chapter06\GenericInputForm\InputType;

class RegisterFormBuilder implements FormBuilderInterface
{
    public function build(FormGeneratorInterface $formGenerator): void
    {
        $formGenerator->addInput(InputType::Text, ['label' => 'Username', 'name' => 'username']);
        $formGenerator->addInput(InputType::Password, ['label' => 'Password', 'name' => 'password']);
        $formGenerator->addInput(InputType::Email, ['label' => 'Email', 'name' => 'email']);
    }
}