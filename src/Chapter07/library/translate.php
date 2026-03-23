<?php
use Cookbook\REST\GenAiConnect;
define('TRANSLATE_MAX_LEN', 1024);
#[translate(
    "@param GenAiConnect connect : GenAiConnect instance",
    "@param string lang_from : ISO 639-1 language code for source language",
    "@param string lang_to   : ISO 639-1 language code for destination language",
    "@param string phrase    : Sentence to translate",
    "@return string response : Translated phrase",
    "@throws InvalidArgumentException"
)]    
function translate(GenAiConnect $connect, array $args)
{
    require_once __DIR__ . '/includes/verify_lang.php';
    $lang_from  = $args['lang_from'] ?? 'Unknown';
    $lang_to  = $args['lang_to'] ?? 'Unknown';
    $lang_from_txt = '';
    $lang_to_txt = '';
    $phrase = $args['phrase'] ?? '';
    $usage = 'USAGE: '
           . 'lang_from : ISO 639-1 language code for source language, '
           . 'lang_to   : ISO 639-1 language code for destination language, '
           . 'phrase    : Sentence to translate';
    if (!verify_lang($lang_from, $lang_from_txt)) {
        throw new \InvalidArgumentException("Invalid source ISO 639-1 language code: $lang_from " . $usage);
    }
    if (!verify_lang($lang_to, $lang_to_txt)) {
        throw new \InvalidArgumentException("Invalid destination ISO 639-1 language code: $lang_to " . $usage);
    }
    if (empty($phrase)) {
        throw new \InvalidArgumentException('Phrase to be translated is empty. ' . $usage);
    }
    if (strlen($phrase) > TRANSLATE_MAX_LEN) {
        throw new \InvalidArgumentException('Phrase must be ' . TRANSLATE_MAX_LEN . ' characters or less. ' . $usage);
    }
    $prompt = "Translate the following phrase from $lang_from_txt to $lang_to_txt."
            . "Return only the translated phrase with no explanation.\n\nPhrase: $phrase";
    return $connect->genAIcall($prompt);
}
