<?php

use Cookbook\Chapter08\Translations\Translator;
use Cookbook\Chapter08\Translations\LanguageCode;

include __DIR__ . '/../../vendor/autoload.php';

$languageParam = $_GET['lang'] ?? 'en';
$translator = new Translator();
try {
    $language = LanguageCode::from($languageParam);
} catch (ValueError $e) {
    $language = LanguageCode::EN;
} finally {
    $translations = $translator->loadTranslations($language);
}

echo "<!DOCTYPE html>
<html lang='{$language->value}'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Translations Example</title>
</head>
<body>
    <p>Language code: " . $language->value . "</p>
    <p>" . $translator->translate('greeting', $translations) . "</p>
    <p>" . $translator->translate('howAreYou', $translations) . "</p>
</body>
</html>";
