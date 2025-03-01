<?php

namespace Cookbook\Chapter08\Translations;

class Translator
{
    public function loadTranslations(LanguageCode $language, $extension = ".json"): array
    {
        $file = "./translations/{$language->value}.json";
        if (!file_exists($file)) {
            $file = "./translations/" . LanguageCode::EN . $extension;
        }

        return json_decode(file_get_contents($file), true) ?: [];
    }

    public function translate(string $key, array $translations): string
    {
        return $translations[$key] ?? "[$key]";
    }
}