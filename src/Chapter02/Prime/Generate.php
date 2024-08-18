<?php
namespace Cookbook\Chapter02\Prime;

use Generator;
use SplFixedArray;
class Generate
{
	public ?SplFixedArray $arr = NULL;
	protected function simpleGetNext(float $min) : float
	{
		$test = TRUE;
		while ($test) {
			// simple test to see if $min is divisible by any number up to itself
			for($i = 2; $i < $min; $i++) {
				if(($min % $i) === 0) {
					$test = FALSE;
					break;
				}
			}
			$min++;
		}
		return $min;
	}
	public function getNextPrime(float &$min, string $algo = 'simple') : float
	{
		return match ($algo) {
			'simple' => $this->simpleGetNext($min),
			default => $this->simpleGetNext($min)
		};
	}
}
