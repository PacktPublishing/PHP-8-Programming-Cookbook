<?php

namespace Cookbook\Chapter06\GenericInputForm;

use Cookbook\Chapter06\GenericInputForm\InputType;
use Exception;

class GenericFormGenerator
{
    private array $inputForms = [];

    public function generate(string $formName, string $submitUrl): string
    {
        if (empty($this->inputForms)) {
            throw new Exception('No input forms have been added.');
        }

        $form = "<form name='$formName' action='$submitUrl' method='POST'>";

        foreach ($this->inputForms as $input) {
            $form .= $input;
        }

        $form .= "<div>
                    <button type='submit' name='submit'>Submit</button>
                  </div>
                </form>";

        return $form;
    }

    public function addInput(InputType $inputType, string $label, string $name): void
    {
        $this->inputForms[] = $this->renderInputForm($inputType, $label, $name);
    }

    private function renderInputForm(InputType $inputType, string $label, string $name): string
    {
        $input = "<div>
                    <label>$label</label>
                    <input type='$inputType->name' name='$name'>
                  </div>";

        return $input;
    }

}