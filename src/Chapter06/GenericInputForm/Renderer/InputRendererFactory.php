<?php

namespace Cookbook\Chapter06\GenericInputForm\Renderer;

use Cookbook\Chapter06\GenericInputForm\InputType;

class InputRendererFactory
{
    public static function createRenderer(InputType $inputType): InputRendererInterface
    {
        // Map input types to specific renderer classes
        $renderers = [
            InputType::Text->name => TextInputRenderer::class,
            InputType::Password->name => TextInputRenderer::class,
            InputType::Email->name => TextInputRenderer::class,
            InputType::Radio->name => RadioInputRenderer::class,
            InputType::Select->name => SelectInputRenderer::class,
        ];

        if (!isset($renderers[$inputType->name])) {
            throw new \Exception("Unsupported input type");
        }

        // Dynamically instantiate the appropriate renderer
        return new $renderers[$inputType->name]();
    }
}