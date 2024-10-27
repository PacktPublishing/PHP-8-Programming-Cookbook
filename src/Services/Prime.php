<?php
namespace Cookbook\Services;
// See: https://en.wikipedia.org/wiki/Miller%E2%80%93Rabin_primality_test
use Generator;
use function gmp_abs;  // NOTE: requires the bcmath extension
use function gmp_prob_prime;
class Prime
{
    // Miller-Rabin Primality Test
    #[Services\generate("string min : minimum value expressed as a string; int num : number of primes",
                        "Returns Generator instance which is iterable")]
    public function generate(string $min, int $num) : Generator
    {
        $candidate = gmp_abs($min);
        while ($num >= 0) {
            // Uses Miller-Rabin to determine probability of prime
            if (gmp_prob_prime($candidate)) {
                yield $candidate;
                $num--;
            }
            $candidate++;
        }
    }
}
