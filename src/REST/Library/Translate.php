<?php
namespace Cookbook\REST\Library;
use Cookbook\REST\GenAiConnect;
use Cookbook\REST\MicroserviceInterface;
use Cookbook\REST\Library\Traits\VerifyLangTrait;
class Translate implements MicroserviceInterface
{
    use VerifyLangTrait;
    public const TRANSLATE_MAX_LEN = 1024;
    #[Cookbook\REST\Library\translate\__invoke(
        "@param GenAiConnect connect : GenAiConnect instance",
        "@param string lang_from : ISO 639-1 language code for source language",
        "@param string lang_to   : ISO 639-1 language code for destination language",
        "@param string phrase    : Sentence to translate",
        "@return string response : Translated phrase",
        "@throws InvalidArgumentException"
    )]    
    public function __invoke(GenAiConnect $connect, iterable $args) : string
    {
        $lang_from  = $args['lang_from'] ?? 'Unknown';
        $lang_to  = $args['lang_to'] ?? 'Unknown';
        $lang_from_txt = '';
        $lang_to_txt = '';
        $phrase = $args['phrase'] ?? '';
        $usage = <<<EOT
        USAGE: 
        lang_from : ISO 639-1 language code for source language
        lang_to   : ISO 639-1 language code for destination language
        phrase    : Sentence to translate
        EOT;
        if (!$this->verify_lang($lang_from, $lang_from_txt)) {
            throw new \InvalidArgumentException("Invalid source ISO 639-1 language code: $lang_from " . $usage);
        }
        if (!$this->verify_lang($lang_to, $lang_to_txt)) {
            throw new \InvalidArgumentException("Invalid destination ISO 639-1 language code: $lang_to " . $usage);
        }
        if (empty($phrase)) {
            throw new \InvalidArgumentException('Phrase to be translated is empty. ' . $usage);
        }
        if (strlen($phrase) > static::TRANSLATE_MAX_LEN) {
            throw new \InvalidArgumentException('Phrase must be ' . static::TRANSLATE_MAX_LEN . ' characters or less. ' . $usage);
        }
        $prompt = "Translate the following phrase from $lang_from_txt to $lang_to_txt."
                . "Return only the translated phrase with no explanation.\n\nPhrase: $phrase";
        return $connect->genAIcall($prompt);
    }
}
