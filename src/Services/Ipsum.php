<?php
namespace Cookbook\Services;
#[Ipsum("Provides Lorem Ipsum",
    "See: https://loripsum.net/")]
class Ipsum
{
    public const URL = 'https://loripsum.net/api';
    #[Ipsum\__invoke("opts: (integer) - The number of paragraphs to generate.
                     short, medium, long, verylong - The average length of a paragraph.")]
    public function __invoke(array $opts)
    {
        $url = static::URL . '/' . implode('/', $opts);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        return curl_exec($url);
    }
}

                             
