<?php

namespace Cookbook\Chapter06\GenericInputForm\Renderer;

use Cookbook\Chapter06\GenericInputForm\InputType;

class TextInputRenderer implements InputRendererInterface
{
    public function render(InputType $inputType, array $options): string
    {
        $label = $options['label'];
        $name = $options['name'];
        $input = "<div>
                    <label>$label</label>
                    <input type='$inputType->name' name='$name'>
                  </div>";

        return $input;
    }
}