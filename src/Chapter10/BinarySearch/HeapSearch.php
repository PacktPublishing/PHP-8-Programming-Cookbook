<?php

namespace Cookbook\Chapter10\BinarySearch;

use SplHeap;

class HeapSearch extends SplHeap
{
    private array $terms;

    public function __construct(array $terms)
    {
        $this->terms = $terms;
    }

    public function compare(mixed $a, mixed $b): int
    {
        $key = array_key_first($this->terms);

        if (isset($a[$key]) && isset($b[$key])) {
            return $a[$key] <=> $b[$key];
        }

        if (isset($a[$key])) {
            return 1;
        }

        if (isset($b[$key])) {
            return -1;
        }

        return 0;
    }

    public function match(array $item): bool
    {
        foreach ($this->terms as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $subK => $subV) {
                    if (($item[$k][$subK] ?? null) !== $subV) {
                        return false;
                    }
                }
            } elseif (($item[$k] ?? null) !== $v) {
                return false;
            }
        }
        return true;
    }
}