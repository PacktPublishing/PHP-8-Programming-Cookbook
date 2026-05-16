<?php

declare(strict_types=1);

namespace Cookbook\Chapter12\Password;

final class PasswordOptions
{
    private int $length = 20;
    private bool $useLower = true;
    private bool $useUpper = true;
    private bool $useDigits = true;
    private bool $useSymbols = false;
    private bool $noAmbiguous = false;

    public function getLength(): int
    {
        return $this->length;
    }

    public function isUseLower(): bool
    {
        return $this->useLower;
    }

    public function isUseUpper(): bool
    {
        return $this->useUpper;
    }

    public function isUseDigits(): bool
    {
        return $this->useDigits;
    }

    public function isUseSymbols(): bool
    {
        return $this->useSymbols;
    }

    public function isNoAmbiguous(): bool
    {
        return $this->noAmbiguous;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function setUseLower(bool $useLower): self
    {
        $this->useLower = $useLower;

        return $this;
    }

    public function setUseUpper(bool $useUpper): self
    {
        $this->useUpper = $useUpper;

        return $this;
    }

    public function setUseDigits(bool $useDigits): self
    {
        $this->useDigits = $useDigits;

        return $this;
    }

    public function setUseSymbols(bool $useSymbols): self
    {
        $this->useSymbols = $useSymbols;

        return $this;
    }

    public function setNoAmbiguous(bool $noAmbiguous): self
    {
        $this->noAmbiguous = $noAmbiguous;

        return $this;
    }
}
