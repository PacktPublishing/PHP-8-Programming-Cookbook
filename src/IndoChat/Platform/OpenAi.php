<?php
namespace Cookbook\IndoChat\Platform;

class OpenAi implements PlatformInterface
{
    public function get(array $arr) : string
    {
        return $arr['output'][0]['content'][0]['text'] ?? '';
    }
}
