<?php
namespace Cookbook\Chapter02\Prime;
use Exception;
class Generate
{
	public float $min = 0;
	protected function simpleGetNext(float $min) : float
	{
		$test = TRUE;
		$max  = $min;
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
	public function getNextPrime(float $min, array &$gen, string $algo = 'simple') : float
	{
		while (!in_array($min, $gen)) {
			$min = match ($algo) {
				'simple' => $this->simpleGetNext($min),
				default => $this->simpleGetNext($min)
			};
		}
		$gen[] = $min;
		return $min;
	}
}
