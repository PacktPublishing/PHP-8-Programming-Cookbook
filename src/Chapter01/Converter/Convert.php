<?php
namespace Cookbook\Chapter01\Converter;
use InvalidArgumentException;
// because this function is in the global namespace, you can declare it in advance
// by doing so you gain a tiny bit of performance as PHP knows where to fuoind the function
// otherwise PHP will attempt to find Cookbook\Chapter01\Converter() first, which wastes time
use function preg_replace_callback_array;
class Convert
{
    public const PROBLEM       = '// ******* PROBLEM: ';
    public const ERR_NOT_FOUND = 'ERROR: this file is not found %s';
    public const LF_REPLACE    = ' --LF-- ';
    public const CONVERT_KEY   = 'convert';
    public string $contents    = '';
    public array $post_op      = [];    // array of post-op callbacks
    public array $regex_callbacks = [];
    #[Convert\__construct(
        "Accepts a config array and builds rules",
        "param_01 : <iterable> config"
    )]
    public function __construct(public iterable $config = []) 
    {
        $this->regex_callbacks = [
            // looks for method of same name as class
            '/' . static::LF_REPLACE . 'class (.+?)\b.*?{/' => [$this, '_00'],
            // looks for 3rd arg for define()
            '/\bdefine\((.+?,.+?),.+?\)/' => [$this, '_01'],
            // looks for __autoload()
            '/\bfunction __autoload\b/' => [$this, '_02'],
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
        // run post-op callbacks
        foreach ($this->post_op as $callback) {
            $callback($this->contents);
        }
        // convert " --LF-- " into PHP_EOL
        $this->contents = str_replace(static::LF_REPLACE, PHP_EOL, $this->contents);        
        return $this->contents;
    }
    #[Convert\_00(
        "flags method same name as class if __construct() not found"
    )]
    public function _00($match)
    {
        $txt = $match[0];
        $search = 'function ' . $match[1];
        if (!str_contains($this->contents, '__construct')
            && stripos($this->contents, $search) !== FALSE) {
            $txt = static::LF_REPLACE . static::PROBLEM
                . 'A method with the same name as the class '
                . 'is no longer used as a default construct method.' 
                . 'This method was converted to __construct.'
                . $txt;
            // this is a callable class invoked in __destruct()
            $this->post_op[] = new class ($search) {
                public function __construct(public string $search) {}
                public function __invoke(string &$contents)
                { 
                    $contents = str_ireplace(
                        $this->search, 
                        'function __construct', 
                        $contents);
                }
            };
        }
        return $txt;
    }
    #[Convert\_01(
        "looks for 3rd arg to define() being set"
    )]
    public function _01($match)
    {
        return static::PROBLEM
               . 'The 3rd argument to "define()" is now ignored.'
               . Convert::LF_REPLACE
               . 'define(' . $match[1] . ')';
    }
    #[Convert\_02(
        "looks for __autoload()"
    )]
    public function _02($match) 
    {
        return static::PROBLEM
               . '"__autoload()" is longer supported for autoloading. '
               . 'Use "spl_autoload_register()".'
               . Convert::LF_REPLACE
               . "spl_autoload_register('autoload');"
               . Convert::LF_REPLACE
               . str_replace('__autoload', 'autoload', $match[0]);
    }
}
