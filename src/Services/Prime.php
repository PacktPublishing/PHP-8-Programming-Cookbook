<?php
namespace Cookbook\Services;
// See: https://en.wikipedia.org/wiki/Miller%E2%80%93Rabin_primality_test
use Generator;
use function gmp_abs;  // NOTE: requires the gmp extension
use function gmp_prob_prime;
class Prime
{
    const CONTROL = 100;    // don't go past 100 attempts if empty
    // Miller-Rabin Primality Test
    #[Services\generate("string min : minimum value expressed as a string",
                        "int num : number of primes",
                        "Returns iterable Generator instance")]
    public function generate(string $min, int $num = 1) : Generator
    {
        $candidate = gmp_abs($min);
        for ($x = 0; $x < $num; $x++) {
            // Uses Miller-Rabin to determine next prime
            $candidate = gmp_nextprime($candidate);
            yield $candidate;
        }
    }
}
