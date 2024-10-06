<?php
namespace Cookbook\Chapter01\Converter;
use ArrayObject;
use InvalidArgumentException;
// because this function is in the global namespace, you can declare it in advance
// by doing so you gain a tiny bit of performance as PHP knows where to fuoind the function
// otherwise PHP will attempt to find Cookbook\Chapter01\Converter() first, which wastes time
use function preg_replace_callback_array;
use function file_exists;
use function file_get_contents;
use function str_replace;

class Convert
{
    public const PROBLEM       = '// ******* DETECTED: ';
    public const ERR_NOT_FOUND = 'ERROR: this file is not found %s';
    public const LF_REPLACE    = ' --LF-- ';
    public const CONVERT_KEY   = 'convert';
    public const RULES_KEY     = 'rules';
    public string $contents    = '';
    public array $rules_list   = [];
    public ?iterable $post_op = NULL; // post-op callbacks
    public ?iterable $regex_callbacks = NULL;
    #[Convert\__construct(
        "Accepts a config array and builds rules",
        "param_01 : <iterable> config"
    )]
    public function __construct(public iterable $config = []) 
    {
        $this->post_op = new ArrayObject();
        $this->rules_list = $config[static::RULES_KEY] ?? [];
        $this->regex_callbacks = [];
    }
    #[Convert\convert(
        "runs preg_replace_callback_array()",
        "param_01 : <string> filename"
    )]
    public function convert(string $filename)
    {
        // fail early if file not found
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(sprintf(static::ERR_NOT_FOUND, $filename));
        }
        // grab contents
        $this->contents = file_get_contents($filename);
        // convert PHP_EOL into " --LF-- "
        $this->contents = str_replace(PHP_EOL, static::LF_REPLACE, $this->contents);
        // load rules
        foreach ($this->rules_list as $class) {
            $callback = new $class($this->contents, $this->post_op);
            if ($callback instanceof RulesInterface) {
                $this->regex_callbacks[$callback::REGEX] = $callback;
            }
        }
        // this is the main process
        $this->contents = preg_replace_callback_array($this->regex_callbacks, $this->contents);
        // also process any callbacks coming from the config file
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
}
