<?php

namespace Cookbook\Chapter08\Cleaner;

class InputCleaner
{
    public function clean($input): string
    {
        // Decode any HTML Entities like < >
        $decodedInput = html_entity_decode($input, ENT_QUOTES, 'UTF-8');

        // Sanitize
        $sanitizedInput = htmlspecialchars($decodedInput, ENT_QUOTES, 'UTF-8');

        // Strip all tags.
        $sanitizedInput = strip_tags($sanitizedInput);

        return $sanitizedInput;
    }
}