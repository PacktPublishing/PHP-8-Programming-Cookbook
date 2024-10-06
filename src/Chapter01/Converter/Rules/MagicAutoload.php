<?php
namespace Cookbook\Chapter01\Converter\Rules;
use Cookbook\Chapter01\Converter\{Convert,RulesInterface};
// looks __autoload() method
class MagicAutoload implements RulesInterface
{
    public const REGEX = '/\bfunction __autoload\b/';
    #[ClassConstructor\__construct(
        "Accepts contents and post-op iterator"
    )]
    public function __construct(public string &$contents, public iterable $post_op) {}
    #[ClassConstructor\__invoke(
        "flags method same name as class if __construct() not found",
    )]
    public function __invoke(array $match)
    {
        return Convert::PROBLEM
               . '"__autoload()" is longer supported for autoloading. '
               . 'Use "spl_autoload_register()".'
               . Convert::LF_REPLACE
               . "spl_autoload_register('autoload');"
               . Convert::LF_REPLACE
               . str_replace('__autoload', 'autoload', $match[0]);
    }
}
