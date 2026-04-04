<?php
namespace Cookbook\Services;
use ArrayObject;
use Psr\Container\ContainerInterface;
#[Cookbook\Services\OpenAiPlatform("Provides output from Monica")]
class MonicaPlatform
{
    public const REGEX = '!\"content\"\:\"(.*?)"!';
    public function __invoke(string $json) : string
    {
        $json = str_replace('\u0022', '"', $json);
        if (!preg_match(self::REGEX, $json, $match)) {
            return json_encode(['success' => false, 'message' => 'No Content', 'return' => $json]);
        } else {
            return json_encode(['success' => true, 'message' => ($match[1] ?? 'No Content')]);
        }
    }
}
