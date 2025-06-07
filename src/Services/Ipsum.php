<?php
namespace Cookbook\Services;
use SplFileObject;
#[Ipsum("Provides random text")]
class Ipsum
{
    public string $fn = '';
    public array $paragraphs = [];
    public ?SplFileObject $obj = NULL;
    public const FN  = __DIR__ . '/../../data/war_and_peace.txt';
    public const CACHE_FN = __DIR__ . '/../../data/ipsum_cache.txt';
    public const SIZE = 40; // line length
    #[Ipsum\__construct("string \$fn : The source text file")]
    public function __construct(string $fn = '')
    {
        $this->fn = (empty($fn)) ? static::FN : $fn;
        $this->obj = new SplFileObject($this->fn, 'r');
        $this->paragraphs = $this->analyze();
    }
    #[Ipsum\analyze("Determines line numbers for paragraphs within text")]
    public function analyze(int $size = 0) : array
    {
        $size = $size ?: static::SIZE;
        $paragraphs = [];
        $this->obj->rewind();
        // look for line length >= $size
        do {
            $tell = $this->obj->ftell();
            $line = $this->obj->fgets() ?? '';
            $len = strlen($line);
            $line = trim($line);
        } while (!$this->obj->eof() && $len < $size);
        $paragraphs[] = $tell;  // get current offset
        while (!$this->obj->eof()) {
            // 1: look for next non-blank line and note its line #
            do {
                $line = trim($this->obj->fgets() ?? '');
            } while (!$this->obj->eof() && empty($line));
            // look for line length >= $size
            while (!$this->obj->eof() && strlen($line) < $size) {
                $tell = $this->obj->ftell();
                $line = $this->obj->fgets() ?? '';
                $len = strlen($line);
                $line = trim($line);
            }
            $paragraphs[] = $tell;  // get current offset
            // 2: loop until you get a blank link
            if ($this->obj->eof()) break;
            do {
                $line = trim($this->obj->fgets() ?? '');
            } while (!$this->obj->eof() && !empty($line));
        }
        return $paragraphs;
    }
    #[Ipsum\__invoke("Returns \$num random paragraph(s)")]
    public function __invoke(int $num = 1)
    {
        $text = '';
        for ($x = 0; $x < $num; $x++) {
            $offset = $this->paragraphs[array_rand($this->paragraphs)];
            $text .= $this->getParagraph($offset);
        }
        return $text;
    }
    #[Ipsum\getParagraph("Returns a specific paragraph \$offset")]
    public function getParagraph(int $offset)
    {
        $text = '';
        $this->obj->rewind();
        $this->obj->fseek($offset);
        $line = trim($this->obj->fgets() ?? '');
        while (!$this->obj->eof() && !empty($line)) {
            $text .= $line . PHP_EOL;
            $line = trim($this->obj->fgets() ?? '');
        }
        return $text . PHP_EOL;
    }
}
