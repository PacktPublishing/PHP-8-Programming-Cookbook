<?php
namespace Cookbook\Services;
#[Ipsum("Provides random text")]
class Ipsum
{
    public const FN  = __DIR__ . '/../../data/war_and_peace.txt';
    #[Ipsum\__invoke(
        "int \$num : The number of paragraphs to generate.",
        "string \$fn : The source text file")]
    public function __invoke(int $num, string $fn = '')
    {
        $fn = (empty($fn)) ? static::FN : $fn;
        $text = file($fn);
        $count = count($text);  // Number Lines
        
        unset($text);        
    }
}

                             
