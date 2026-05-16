<?php

namespace Cookbook\Chapter12\Captcha;

interface CaptchaInterface
{
    /**
     * @param string $token
     * @param string|null $remoteIp
     * @return array
     */
    public function verify(string $token, ?string $remoteIp = null): array;
}
