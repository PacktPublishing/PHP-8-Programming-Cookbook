<?php
define('TRANSLATE_MAX_LEN', 1024);
#[translate(
    "@param GenAiConnect connect : GenAiConnect instance",
    "@param string iso2_from : ISO2 code for source language",
    "@param string iso2_to   : ISO2 code for destination language",
    "@param string phrase    : Sentence to translate",
    "@return string response : Translated phrase",
    "@throws InvalidArgumentException"
)]    
function translate(GenAiConnect $connect, ...$args)
{
    require_once __DIR__ . '/verify_iso2.php';
    $iso2_from = $args['iso2_from'] ?? 'Unknown';
    $iso2_to  = $args['iso2_to'] ?? 'Unknown';
    $phrase = $args['phrase'] ?? '';
    if (!verify_iso2($iso2_from)) {
        throw new \InvalidArgumentException("Invalid source ISO2 code: $iso2_from");
    }
    if (!verify_iso2($iso2_to)) {
        throw new \InvalidArgumentException("Invalid destination ISO2 code: $iso2_to");
    }
    if (empty($phrase)) {
        throw new \InvalidArgumentException('Phrase to be translated is empty');
    }
    if (strlen($phrase) > TRANSLATE_MAX_LEN) {
        throw new \InvalidArgumentException('Phrase must be ' . TRANSLATE_MAX_LEN . ' characters or less');
    }
    $prompt = "Translate the following phrase from the language whose ISO2 country code is '$iso2_from' "
            . "to the language whose ISO2 country code is '$iso2_to'. "
            . "Return only the translated phrase with no explanation.\n\nPhrase: $phrase";
    return $connect->makeGenAIcall($prompt);
}
