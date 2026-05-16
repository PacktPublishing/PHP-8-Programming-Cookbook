<?php

namespace Cookbook\Chapter12\Captcha;

final class ReCaptcha implements CaptchaInterface
{
    private ?string $secret = null;
    private ?string $expectedHost = null;
    private int $timeoutSeconds = 5;
    private bool $initialised = false;

    private string $endpoint = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct()
    {
        // No arguments; properties must be set via setters
    }

    public function initialise(): void
    {
        if ($this->secret === null) {
            throw new \RuntimeException('ReCaptcha secret must be set before initialisation.');
        }

        $this->initialised = true;
    }

    public function verify(string $token, ?string $remoteIp = null): array
    {
        if (!$this->initialised) {
            return ['ok' => false, 'error' => 'recaptcha not initialised'];
        }

        if ($token === '') {
            return ['ok' => false, 'error' => 'missing token'];
        }

        $post = [
            'secret'   => $this->secret,
            'response' => $token,
        ];
        if ($remoteIp !== null && $remoteIp !== '') {
            $post['remoteip'] = $remoteIp;
        }

        $raw = $this->post($this->endpoint, $post);
        if ($raw['error'] !== null) {
            return ['ok' => false, 'error' => 'recaptcha request failed: ' . $raw['error']];
        }

        try {
            /** @var array<string,mixed> $data */
            $data = json_decode($raw['body'] ?? 'null', true, flags: JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return ['ok' => false, 'error' => 'invalid response from recaptcha'];
        }

        if (!(bool)($data['success'] ?? false)) {
            $codes = $data['error-codes'] ?? [];
            $msg = 'recaptcha failed' . (!empty($codes) ? ': ' . implode(', ', (array)$codes) : '');
            return ['ok' => false, 'error' => $msg, 'raw' => $data];
        }

        if ($this->expectedHost !== null && ($data['hostname'] ?? '') !== $this->expectedHost) {
            return ['ok' => false, 'error' => 'invalid hostname', 'raw' => $data];
        }

        return ['ok' => true, 'raw' => $data];
    }

    private function post(string $url, array $fields): array
    {
        $ch = curl_init($url);
        if ($ch === false) {
            return ['body' => null, 'error' => 'curl init failed'];
        }

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($fields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeoutSeconds,
        ]);

        $body = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return ['body' => null, 'error' => $err ?: 'unknown error'];
        }

        return ['body' => $body, 'error' => null];
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function getExpectedHost(): ?string
    {
        return $this->expectedHost;
    }

    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function setExpectedHost(?string $expectedHost): void
    {
        $this->expectedHost = $expectedHost;
    }

    public function setTimeoutSeconds(int $timeoutSeconds): void
    {
        $this->timeoutSeconds = $timeoutSeconds;
    }
}