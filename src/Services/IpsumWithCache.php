<?php
namespace Cookbook\Services;
use SplFileObject;
#[IpsumWithCache("Provides same as Ipsum but uses cache to bypass analyze()")]
class IpsumWithCache extends Ipsum
{
    public ?object $cache = NULL;
    #[Ipsum\__construct("string \$fn : The source text file")]
    public function __construct(string $fn = '')
    {
        $this->fn = (empty($fn)) ? static::FN : $fn;
        $this->obj = new SplFileObject($this->fn, 'r');
        $cache = new Cache();
        if ($cache->has($this->fn)) {
            $this->paragraphs = $cache->get($this->fn);
        } else {
            $this->paragraphs = $this->analyze();
            $cache->set($this->fn, $this->paragraphs);
        }
    }
}
