<?php

namespace Cookbook\Chapter06\GenericInputForm;

use Cookbook\Chapter06\GenericInputForm\Renderer\InputRendererFactory;
use Cookbook\Chapter06\GenericInputForm\Renderer\InputRendererInterface;
use Exception;

class GenericFormGenerator implements FormGeneratorInterface
{
    private array $inputForms = [];

    private InputRendererInterface $renderer;

    public function generate(string $formName, string $submitUrl, array $options = []): string
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

    /**
     * @throws Exception
     */
    public function addInput(InputType $inputType, array $options = []): void
    {
        $this->inputForms[] = $this->renderInputForm($inputType, $options);
    }

    /**
     * @throws Exception
     */
    private function renderInputForm(InputType $inputType, array $options): string
    {
        $renderer = InputRendererFactory::createRenderer($inputType);
        return $renderer->render($inputType, $options);
    }
}