<?php
namespace Cookbook\Services;
// Credit: Miquéias Vinícius -- developed for this book
// See: https://github.com/ryudeveloper/PrimeNumberGenerators/blob/main/PrimeNumGenerate.php
// See: https://en.wikipedia.org/wiki/Miller%E2%80%93Rabin_primality_test
use Generator;
use function bcpowmod;  // NOTE: requires the bcmath extension
use function rand;
use function max;
use function ceil;
class Prime
{
    // Miller-Rabin Primality Test
    #[Services\millerRabin("float n : minimum value; int k : number of primes")]
    protected function millerRabin(float|string $n, int $k = 5)
    {
        // return early for the following:
        if ($n < 2) {
            return false;
        } elseif ($n == 2 || $n == 3) {
            return true;
        } elseif ($n % 2 == 0) {
            return false;
        }

        // Write n-1 as 2^s * d
        $s = 0;
        $d = $n - 1;
        while ($d % 2 == 0) {
            $d /= 2;
            $s++;
        }

        // Test k times
        for ($i = 0; $i < $k; $i++) {
            $a = rand(2, $n - 2);  // Random base between 2 and n-2
            /*
            Fatal error: Uncaught ValueError: bcpowmod(): Argument #3 ($modulus) is not well-formed in /repo/src/Services/Prime.php:37
            Stack trace:
            #0 /repo/src/Services/Prime.php(37): bcpowmod('2533442280528', '30517578125', '1.0E+15')
            #1 /repo/src/Services/Prime.php(61): Cookbook\Services\Prime->millerRabin(1.0E+15)
            #2 /repo/src/Chapter01/ch01_prime.php(10): Cookbook\Services\Prime->generate(1.0E+15, 100)
            #3 {main}
              thrown in /repo/src/Services/Prime.php on line 37
            */
            $x = bcpowmod((string) $a, (string) $d, (string) $n);  // a^d % n

            if ($x == 1 || $x == $n - 1)
            continue;

            $continueLoop = false;
            for ($r = 0; $r < $s - 1; $r++) {
                $x = bcpowmod($x, 2, $n);

                if ($x == $n - 1) {
                $continueLoop = true;
                break;
                }
            }
            if (!$continueLoop) return false;  // Definitely composite
        }
        return true;  // Probably prime
    }
    #[Services\generate("float min : minimum value; int num : number of primes",
                        "Returns Generator instance which is iterable")]
    public function generate(float $min, int $num) : Generator
    {
        $candidate = max(ceil($min), 2);
        while ($num >= 0) {
            if ($this->millerRabin($candidate)) {
                yield $candidate;
                $num--;
            }
            $candidate++;
        }
    }
}
