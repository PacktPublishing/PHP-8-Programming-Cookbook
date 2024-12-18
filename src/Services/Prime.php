<?php
namespace Cookbook\Services;
// See: https://en.wikipedia.org/wiki/Miller%E2%80%93Rabin_primality_test
use GMP; // NOTE: requires the gmp extension
use Generator;
use SplObjectStorage;
use function gmp_abs;  
use function gmp_strval;
use function gmp_nextprime;
use function random_int;
class Prime
{
    const CONTROL = 100;    // don't go past 100 attempts if empty
    // Miller-Rabin Primality Test
    #[Services\generate("int size : size in # of digits of the seed number",
                        "int num : number of primes",
                        "Returns iterable Generator instance")]
    public function generate(int $size, int $num = 1) : Generator
    {
        $fixed = new SplObjectStorage($num);
        $count = $num;
        while ($count > 0) {
            // Generate random seed according to size
            $candidate = gmp_abs($this->randomSeed($size));
            // Uses Miller-Rabin to determine next prime
            $candidate = gmp_nextprime($candidate);
            if (!isset($fixed[$candidate])) {
                $fixed[$candidate] = $count--;
                yield gmp_strval($candidate);
            }
        }
    }
    // Creates random starting seed according to $size
    #[Services\randomSeed("int size : size in # of digits of the seed number",
                        "Returns string num")]
    public function randomSeed(int $size) : string
    {
        // generate random starting number according to $size
        $num = '';
        for ($x = 0; $x < $size; $x++) {
            $num .= (string) random_int(1, 9);
        }
        return $num;
    }
}
