<?php

namespace Cookbook\Chapter06\Select;

class SelectGenerator
{
    public function generate(array $selectOptions, array $elementOptions): string
    {
        $html = "";
        $selectName = $selectOptions['selectName'];
        $selectId = $selectOptions['selectId'];

        // Start the select element
        $html .= "<select name='$selectName' id='$selectId'>";

        // Generate options
        foreach ($elementOptions as $elementOption) {
            $html .= $this->generateOptionElement($elementOption);
        }

        // Close the select element
        $html .= "</select>";

        return $html;
    }

    private function generateOptionElement(array $elementOptions): string
    {
        $value = $elementOptions['option']['optionValue'];
        $text = $elementOptions['option']['optionText'];

        return "<option value='$value'>$text</option>";
    }

}