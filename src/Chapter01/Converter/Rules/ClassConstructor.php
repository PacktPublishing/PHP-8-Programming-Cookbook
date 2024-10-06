<?php
namespace Cookbook\Chapter01\Converter\Rules;
use Cookbook\Chapter01\Converter\{Convert,RulesInterface};
class ClassConstructor implements RulesInterface
{
    // looks for method of same name as class
    public const REGEX = '/' . Convert::LF_REPLACE . 'class (.+?)\b.*?{/';
    #[ClassConstructor\__construct(
        "Accepts post-op iterator"
    )]
    public function __construct(public string &$contents, public iterable $post_op) {}
    #[ClassConstructor\__invoke(
        "flags method same name as class if __construct() not found",
    )]
    public function __invoke(array $match)
    {
        $txt = $match[0];
        $search = 'function ' . $match[1];
        if (!str_contains($this->contents, '__construct')
            && stripos($this->contents, $search) !== FALSE) {
            $txt = Convert::LF_REPLACE . Convert::PROBLEM
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
}
