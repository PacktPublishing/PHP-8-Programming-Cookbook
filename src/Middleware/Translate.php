<?php
namespace Cookbook\Middleware;
use RuntimeException;
use InvalidArgumentException;
use Cookbook\Middleware\BaseHandler;
use Cookbook\Middleware\Traits\VerifyLangTrait;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
class Translate extends BaseHandler
{
    use VerifyLangTrait;
    public const TRANSLATE_MAX_LEN = 1024;
    public const USAGE = 'USAGE: '
        . 'lang_from : ISO 639-1 language code for source language'
        . 'lang_to   : ISO 639-1 language code for destination language'
        . 'phrase    : Sentence to translate';
    #[Cookbook\Middleware\Translate\handle(
        "@param GenAiConnect connect : GenAiConnect instance",
        "@param string lang_from : ISO 639-1 language code for source language",
        "@param string lang_to   : ISO 639-1 language code for destination language",
        "@param string phrase    : Sentence to translate",
        "@return string response : Translated phrase",
        "@throws InvalidArgumentException"
    )]    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = $request->getParsedBody()['data'] ?? '';
        $args = json_decode($data, associative: TRUE, flags: JSON_THROW_ON_ERROR);
        $lang_from  = $args['lang_from'] ?? 'Unknown';
        $lang_to  = $args['lang_to'] ?? 'Unknown';
        $lang_from_txt = '';
        $lang_to_txt = '';
        $phrase = $args['phrase'] ?? '';
        if (!$this->verify_lang($lang_from, $lang_from_txt)) {
            throw new InvalidArgumentException("Invalid source ISO 639-1 language code: $lang_from " . self::USAGE);
        }
        if (!$this->verify_lang($lang_to, $lang_to_txt)) {
            throw new InvalidArgumentException("Invalid destination ISO 639-1 language code: $lang_to " . self::USAGE);
        }
        if (empty($phrase)) {
            throw new InvalidArgumentException('Phrase to be translated is empty. ' . self::USAGE);
        }
        if (strlen($phrase) > static::TRANSLATE_MAX_LEN) {
            throw new InvalidArgumentException('Phrase must be ' . static::TRANSLATE_MAX_LEN . ' characters or less. ' . self::USAGE);
        }
        $connect = $this->container->get('GenAiConnect');
        if (empty($connect)) {
            throw new RuntimeException('Required service is offline. ' . self::USAGE);
        }
        $prompt = "Translate the following phrase from $lang_from_txt to $lang_to_txt."
                . "Return only the translated phrase with no explanation.\n\nPhrase: $phrase";
        return (new JsonResponse($connect->genAIcall($prompt)))->withStatus(200);
    }
}
