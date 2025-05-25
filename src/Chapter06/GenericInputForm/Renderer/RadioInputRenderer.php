<?php

namespace Cookbook\Chapter06\GenericInputForm\Renderer;

use Cookbook\Chapter06\GenericInputForm\InputType;

class RadioInputRenderer implements InputRendererInterface
{
    public function render(InputType $inputType, array $options): string
    {
        $groupOptions = $options['radio_group_options'];
        $elementOptions = $options['radio_element_options'];

        return $this->generate($groupOptions, $elementOptions);
    }

    private function generate(array $groupOptions, array $elementOptions): string
    {
        $html = "";
        $fieldSetTitle = $groupOptions['fieldSetTitle'];
        $html .= "<fieldset><legend>$fieldSetTitle</legend>";

        foreach ($elementOptions as $elementOption) {
            $html .= $this->generateRadioElement($elementOption);
        }

        $html .= "</fieldset>";

        return $html;
    }

    private function generateRadioElement(array $elementOptions): string
    {
        $name = $elementOptions['radio']['radioElementName'];
        $id = $elementOptions['radio']['radioElementId'];
        $value = $elementOptions['radio']['radioElementValue'];
        $labelFor = $elementOptions['label']['labelFor'];
        $labelText = $elementOptions['label']['labelText'];
        return "<input type='radio' name='$name' id='$id' value='$value' /> <label for='$labelFor'>$labelText</label><br />";
    }

    private function buildRadio(array $attributes, array $options): string
    {
        $name = htmlspecialchars($attributes['name'] ?? 'radio', ENT_QUOTES);
        $html = "";

        foreach ($options as $value => $text) {
            $id = "{$name}_" . htmlspecialchars($value, ENT_QUOTES);
            $html .= "<input type='radio' id='$id' name='$name' value='" . htmlspecialchars($value, ENT_QUOTES) . "'>";
            $html .= "<label for='$id'>" . htmlspecialchars($text) . "</label><br />";
        }

        return $html;
    }
}