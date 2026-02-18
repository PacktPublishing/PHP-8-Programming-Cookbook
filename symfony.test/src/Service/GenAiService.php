<?php
namespace App\Service;
use InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\AI\Platform\Platform;
use Symfony\AI\Platform\PlatformInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
class GenAiService
{
    public ?PlatformInterface $platform = NULL;
    public function __construct(
        public string $apiKey, public string $apiBridge, public string $apiModel)
    {
        $factory = '\\Symfony\\AI\\Platform\\Bridge\\' . $apiBridge . '\\PlatformFactory';
        if (!class_exists($factory)) throw new InvalidArgumentExcept('ERROR: provider unavailable');
        $this->platform = $factory::create($this->apiKey, HttpClient::create());
    }
    public function chat(string $text)
    {
        $messages = new MessageBag(Message::ofUser($text));
        return $this->platform->invoke($this->apiModel, $messages);
    }
}
