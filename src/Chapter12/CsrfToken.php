<?php

namespace Cookbook\Chapter12;

class CsrfToken
{
    private ?string $token = null;

    public function __construct()
    {
        // Set sensible session cookie defaults
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize token if not already set
        if (!isset($_SESSION['csrf_token'])) {
            $this->generate();
        } else {
            $this->token = $_SESSION['csrf_token'];
        }
    }

    public function generate(): string
    {
        $this->token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $this->token;
        $_SESSION['csrf_token_time'] = time();
        return $this->token;
    }

    public function validate(?string $submittedToken): bool
    {
        if (empty($submittedToken) || empty($this->token)) {
            return false;
        }

        $isValid = hash_equals($this->token, $submittedToken);

        if ($isValid) {
            // Invalidate token after use (single-use pattern)
            unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
            $this->token = null;
            session_regenerate_id(true);
        }

        return $isValid;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): CsrfToken
    {
        $this->token = $token;
        $_SESSION['csrf_token'] = $token;
        return $this;
    }
}
