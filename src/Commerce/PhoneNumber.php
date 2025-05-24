<?php
namespace Cookbook\Commerce;

use InvalidArgumentException;
class PhoneNumber
{
    public const REGEX = '/\+?(\d){1,3}\D(\d+?)\D(.*)/';
    public const ERR_NUM = 'ERR: number should be in this format: +CC AC NNNN'. PHP_EOL
                         . '     where CC is the country code, AC is the area code, '. PHP_EOL
                         . '     and NNN is the remainder of the number';
    public int $countryCode = 0;
    public int $areaCode = 0;
    public string $number = '';
    public function __construct(string $number) 
    {
        if (!preg_match(self::REGEX, $number, $match)) {
            throw new InvalidArgumentException(self::ERR_NUM);
        }
        $this->countryCode = $match[1] ?? 0;
        $this->areaCode    = $match[2] ?? 0;
        $this->number      = trim($match[3] ?? '');
        if (empty($this->countryCode) 
            || empty($this->areaCode)
            ||  empty($this->number)) 
        {
            throw new InvalidArgumentException(self::ERR_NUM);
        }
    }
}
