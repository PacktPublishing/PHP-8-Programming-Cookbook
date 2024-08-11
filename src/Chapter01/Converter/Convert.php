<?php
namespace Cookbook\Chapter01\Converter;
use InvalidArgumentException;
// because this function is in the global namespace, you can declare it in advance
// by doing so you gain a tiny bit of performance as PHP knows where to fuoind the function
// otherwise PHP will attempt to find Cookbook\Chapter01\Converter() first, which wastes time
use function preg_replace_callback_array;
class Convert
{
	public const PROBLEM       = '// ******* PROBLEM: %s';
	public const ERR_NOT_FOUND = 'ERROR: this file is not found %s';
	public const LF_REPLACE    = ' --LF-- ';
	public const CONVERT_KEY   = 'convert';
	public string $contents    = '';
	public array $regex_callbacks = [];
	public array $callback_msg    = [];

	public function __construct(public iterable $config = []) 
	{
		$this->regex_callbacks = [
			// looks for method of same name as class
			'/' . Convert::LF_REPLACE . 'class\b(.+?)\b/' => [$this, '_00'],
			// looks for 3rd arg for define()
			'/\bdefine\((.+?,.+?),.+?\)/' => [$this, '_01'],
			// looks for __autoload()
			'/\bfunction __autoload\b/' => [$this, '_02'],
		];
		$this->callback_msg = [
			'A method with the same name as the class '
				. 'is no longer used as a default construct method.',
			'The 3rd argument to "define()" is now ignored.',
			'"__autoload()" is longer supported for autoloading. '
				. 'Use "spl_autoload_register()".',
		];
	}

	#[Convert\convert(
		"runs preg_replace_callback_array()",
		"param_01 : <string> filename"
	)]
	public function convert(string $filename)
	{
		if (!file_exists($filename)) {
			throw new InvalidArgumentException(sprintf(static::ERR_NOT_FOUND, $filename));
		}
		// convert PHP_EOL into " --LF-- "
		$this->contents = file_get_contents($filename);
		$this->contents = str_replace(PHP_EOL, static::LF_REPLACE, $this->contents);
		$this->contents = preg_replace_callback_array($this->regex_callbacks, $this->contents);
		if (!empty($this->config[static::CONVERT_KEY])) {
			$this->contents = preg_replace_callback_array($this->config[static::CONVERT_KEY], $this->contents);
		}
		// convert " --LF-- " into PHP_EOL
		$this->contents = str_replace(static::LF_REPLACE, PHP_EOL, $this->contents);		
		return $this->contents;
	}
	#[Convert\getKey(
		"returns an integer key derived from the method name",
		"param_01 : <string> method"
	)]
	#[Convert\getMessage(
		"returns the message associated with this regex",
		"param_01 : <string> method"
	)]
	public function getMessage(string $method)
	{
		$key = (int) substr($method, -2);
		return sprintf(static::PROBLEM, ($this->callback_msg[$key] ?? 'Unknown'));
	}
	#[Convert\justInsertMessage(
		"just inserts the message associated with this regex"
	)]
	public function justInsertMessage(string $method, array &$match)
	{
		$msg = $this->getMessage($method);
		$txt = static::LF_REPLACE . $msg . static::LF_REPLACE;
		$txt .= $match[0] ?? '';
		return $txt;
	}
	#[Convert\_00(
		"flags method same name as class if __construct() not found"
	)]
	public function _00($match)
	{
		if (str_contains($this->contents, '__construct')) {
			$txt = $match[0];
		} elseif (stripos($this->contents, trim($match[1])) !== FALSE) {
			$txt = $this->justInsertMessage(__METHOD__, $match);
		} else {
			$txt = $match[0];
		}
		return $txt;
	}
	#[Convert\_01(
		"looks for 3rd arg to define() being set"
	)]
	public function _01($match)
	{
		return $this->getMessage(__METHOD__)
			   . Convert::LF_REPLACE
			   . 'define(' . $match[1] . ')';
	}
	#[Convert\_02(
		"looks for __autoload()"
	)]
	public function _02($match) 
	{
		return $this->getMessage(__METHOD__)
			   . Convert::LF_REPLACE
			   . "spl_autoload_register('autoload');"
			   . Convert::LF_REPLACE
			   . str_replace('__autoload', 'autoload', $match[0]);
	}
}
