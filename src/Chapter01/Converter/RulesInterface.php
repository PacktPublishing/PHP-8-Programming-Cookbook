<?php
namespace Cookbook\Chapter01\Converter;
// defining __invoke() makes a class directly callable
// as if it is a PHP function
interface RulesInterface
{
    public function __invoke(array $match);
    public function __construct(string &$contents, iterable &$post_op);
}
