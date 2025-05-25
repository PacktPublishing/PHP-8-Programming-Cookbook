<?php

namespace Cookbook\Chapter06\GenericInputForm\Renderer;

use Cookbook\Chapter06\GenericInputForm\InputType;

interface InputRendererInterface
{
    public function render(InputType $inputType, array $options): string;
}