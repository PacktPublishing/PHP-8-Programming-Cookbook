<?php

namespace Cookbook\Chapter06\Radio;

class RadioGenerator
{
    public function generate(array $groupOptions, array $elementOptions): string
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
}