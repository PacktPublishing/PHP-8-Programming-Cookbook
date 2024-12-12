<?php

namespace Cookbook\Chapter06\Factory;

use http\Exception\InvalidArgumentException;

class FormFactory
{
    private array $availableTypes = [
        'radio',
        'select',
    ];

    public function build(array $config): string
    {
        if (!isset($config['elements']) || !isset($config['attributes'])) {
            throw new InvalidArgumentException('Missing "elements" and "attributes" key');
        }

        // Construct the form wrapper.
        $html = "<form";
        foreach ($config['attributes'] as $key => $value) {
            $html .= " $key='$value'";
        }
        $html .= ">";

        // Generate input Elements
        foreach ($config['elements'] as $element) {
            $html .= $this->buildElement($element);
        }

        $html .= "</form>";

        return $html;
    }

    // Can be refactored to use separate classes per input type.
    private function buildElement(array $element): string
    {
        if (!isset($element['type']) || !in_array($element['type'], $this->availableTypes)) {
            throw new \InvalidArgumentException("Invalid type '{$element['type']}'");
        }

        $type = $element['type'];
        $attributes = $element['attributes'] ?? [];
        $html = "<$type ";

        // Add attributes
        foreach ($attributes as $key => $value) {
            $html .= " $key='$value'";
        }

        if ($type === 'select') {
            $html .= ">";
            foreach ($element['options'] as $value => $text) {
                $html .= "<option value='$value'>$text</option>";
            }
            $html .= "</select>";
        } elseif ($type === 'radio') {
            $html .= ">";
            foreach ($element['options'] as $value => $text) {
                $id = "{$element['attributes']['name']}_$value";
                $name = htmlspecialchars($element['attributes']['name']);
                $html .= "<input type='radio' id='$id' name='$name' value='$value'> 
                  <label for='$id'>$text</label><br />";
            }
            $html .= "</radio>";
        }


        return $html;
    }
}
