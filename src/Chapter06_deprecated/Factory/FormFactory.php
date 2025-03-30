<?php

namespace Cookbook\Chapter06\Factory;

use http\Exception\InvalidArgumentException;

class FormFactory
{
    public function build(array $config): string
    {
        if (!isset($config['elements']) || !isset($config['attributes'])) {
            throw new InvalidArgumentException('Missing "elements" and "attributes" keys');
        }

        $html = "<form";
        foreach ($config['attributes'] as $key => $value) {
            $html .= " $key='" . htmlspecialchars($value, ENT_QUOTES) . "'";
        }
        $html .= ">";

        foreach ($config['elements'] as $element) {
            $html .= $this->buildElement($element);
            $html .= "<br />";
        }

        // Add submit button by default
        $html .= $this->buildSubmitButton($config['submit'] ?? []);

        $html .= "</form>";

        return $html;
    }

    private function buildElement(array $element): string
    {
        if (!isset($element['type']) || !$this->isValidType($element['type'])) {
            throw new \InvalidArgumentException("Invalid or missing input type '{$element['type']}'");
        }

        $type = $element['type'];
        $attributes = $element['attributes'] ?? [];
        $html = "";

        switch ($type) {
            case InputType::SELECT->value:
                $html .= $this->buildSelect($attributes, $element['options'] ?? []);
                break;

            case InputType::RADIO->value:
                $html .= $this->buildRadio($attributes, $element['options'] ?? []);
                break;

            case InputType::TEXT->value:
            case InputType::EMAIL->value:
                $html .= $this->buildInput($type, $attributes);
                break;

            default:
                throw new \InvalidArgumentException("Unsupported input type '{$type}'");
        }

        return $html;
    }

    private function isValidType(string $type): bool
    {
        return in_array($type, array_column(InputType::cases(), 'value'), true);
    }

    private function buildSelect(array $attributes, array $options): string
    {
        $html = "<select";
        foreach ($attributes as $key => $value) {
            $html .= " $key='" . htmlspecialchars($value, ENT_QUOTES) . "'";
        }
        $html .= ">";

        foreach ($options as $value => $text) {
            $html .= "<option value='" . htmlspecialchars($value, ENT_QUOTES) . "'>"
                . htmlspecialchars($text) . "</option>";
        }

        $html .= "</select>";
        return $html;
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

    private function buildInput(string $type, array $attributes): string
    {
        $html = "<input type='$type'";
        foreach ($attributes as $key => $value) {
            $html .= " $key='" . htmlspecialchars($value, ENT_QUOTES) . "'";
        }
        $html .= " />";
        return $html;
    }

    private function buildSubmitButton(array $attributes): string
    {
        $html = "<button type='submit'";
        foreach ($attributes as $key => $value) {
            $html .= " $key='" . htmlspecialchars($value, ENT_QUOTES) . "'";
        }
        $html .= ">" . ($attributes['label'] ?? 'Submit') . "</button>";
        return $html;
    }
}

