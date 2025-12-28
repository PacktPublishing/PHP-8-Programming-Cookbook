<?php
namespace Test;
class Seven
{
	public $config = [];
	public function seven(array $config)
	{
		$this->config = $config;
	}
	public function add(real $a, real $b) 
	{
		return (real) $a + $b;
	}
	public function match(string $str, float $f)
	{
		return str_contains($str, $f);
	}
	public function mixed(string $str, int $factor = 1)
	{
		$new   = '';
		for ($x = 0; $x < strlen($str); $x++) {
			$new .= chr(ord($str[$x]) + $factor);
		}
		return $new;
	}
	public function getConf(string $key)
	{
		return $this->config[$key] ?? NULL;
	}
}
