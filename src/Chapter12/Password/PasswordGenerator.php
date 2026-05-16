<?php

declare(strict_types=1);

namespace Cookbook\Chapter12\Password;

use InvalidArgumentException;

final class PasswordGenerator
{
    private const MIN_LEN = 8;
    private const MAX_LEN = 128;
    private const MAX_COUNT = 100;

    private const LOWER = 'abcdefghijklmnopqrstuvwxyz';
    private const UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const DIGITS = '0123456789';
    private const SYMBOLS = '!@#$%^&*()-_=+[]{};:,.?';

    /** @var array<int, string> */
    private const AMBIGUOUS = [
        '0', 'O', 'o', '1', 'l', 'I', '|', '`', "'", '"', '~', ';', '.', ',',
    ];

    private int $length = 20;
    private bool $useLower = true;
    private bool $useUpper = true;
    private bool $useDigits = true;
    private bool $useSymbols = false;
    private bool $noAmbiguous = false;

    public function __construct()
    {
        // Configure via setters or applyOptions().
    }

    /** Configure from options model (no arrays). */
    public function applyOptions(PasswordOptions $options): self
    {
        $this->setLength($options->getLength());
        $this->setUseLower($options->isUseLower());
        $this->setUseUpper($options->isUseUpper());
        $this->setUseDigits($options->isUseDigits());
        $this->setUseSymbols($options->isUseSymbols());
        $this->setNoAmbiguous($options->isNoAmbiguous());

        return $this;
    }

    public function setLength(int $length): self
    {
        if ($length < self::MIN_LEN || $length > self::MAX_LEN) {
            throw new InvalidArgumentException(
                sprintf('Length must be between %d and %d.', self::MIN_LEN, self::MAX_LEN)
            );
        }
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

    public function generate(): string
    {
        $sets = [];
        if ($this->useLower) {
            $sets[] = self::LOWER;
        }
        if ($this->useUpper) {
            $sets[] = self::UPPER;
        }
        if ($this->useDigits) {
            $sets[] = self::DIGITS;
        }
        if ($this->useSymbols) {
            $sets[] = self::SYMBOLS;
        }

        if ($sets === []) {
            throw new InvalidArgumentException('At least one character class must be enabled.');
        }

        $pool = $this->uniqueChars(implode('', $sets));

        if ($this->noAmbiguous) {
            $pool = $this->removeChars($pool, self::AMBIGUOUS);

            foreach ($sets as $i => $set) {
                $clean = $this->removeChars($this->uniqueChars($set), self::AMBIGUOUS);
                if ($clean === '') {
                    unset($sets[$i]);
                } else {
                    $sets[$i] = $clean;
                }
            }

            $sets = array_values($sets);

            if ($pool === '') {
                throw new InvalidArgumentException(
                    'Character pool became empty after removing ambiguous characters.'
                );
            }
        }

        $passwordChars = [];

        foreach ($sets as $set) {
            $passwordChars[] = $this->randomCharFrom($set);
        }

        while (count($passwordChars) < $this->length) {
            $passwordChars[] = $this->randomCharFrom($pool);
        }

        $this->secureShuffle($passwordChars);

        return implode('', $passwordChars);
    }

    /**
     * @return array<int, string>
     */
    public function generateMany(int $count): array
    {
        if ($count < 1 || $count > self::MAX_COUNT) {
            throw new InvalidArgumentException(
                sprintf('Count must be between 1 and %d.', self::MAX_COUNT)
            );
        }

        $out = [];
        for ($i = 0; $i < $count; $i++) {
            $out[] = $this->generate();
        }

        return $out;
    }

    private function randomCharFrom(string $chars): string
    {
        $idx = random_int(0, mb_strlen($chars, '8bit') - 1);
        return $chars[$idx];
    }

    private function secureShuffle(array &$arr): void
    {
        for ($i = count($arr) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$arr[$i], $arr[$j]] = [$arr[$j], $arr[$i]];
        }
    }

    private function uniqueChars(string $s): string
    {
        $seen = [];
        $out = '';
        $len = strlen($s);

        for ($i = 0; $i < $len; $i++) {
            $ch = $s[$i];
            if (!isset($seen[$ch])) {
                $seen[$ch] = true;
                $out .= $ch;
            }
        }

        return $out;
    }

    /**
     * @param array<int, string> $charsToRemove
     */
    private function removeChars(string $base, array $charsToRemove): string
    {
        $map = array_fill_keys($charsToRemove, true);
        $out = '';
        $len = strlen($base);

        for ($i = 0; $i < $len; $i++) {
            $ch = $base[$i];
            if (!isset($map[$ch])) {
                $out .= $ch;
            }
        }

        return $out;
    }
}
