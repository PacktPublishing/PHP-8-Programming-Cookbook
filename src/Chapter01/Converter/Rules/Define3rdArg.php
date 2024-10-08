<?php
namespace Cookbook\Chapter01\Converter\Rules;
use Cookbook\Chapter01\Converter\{Convert,RulesInterface};
// checks for 3rd argument in define()
class Define3rdArg implements RulesInterface
{
    public const REGEX = '/\bdefine\((.+?,.+?),.+?\)/';
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
               . 'The 3rd argument to "define()" is now ignored.'
               . Convert::LF_REPLACE
               . 'define(' . $match[1] . ')';
    }
}
