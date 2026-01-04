<?php
namespace Cookbook\Database;
use Generator;
use WeakReference;
class PostCodeStorage
{
    public array $storage = [];
    public function storeInfoUsingArray(Generator $result) : int
    {
        $count = 0;
        $this->storage = [];
        foreach ($result as $obj) {
            $this->storage[] = $obj;
            $count++;
        }
        return $count;
    }
    public function storeInfoUsingWeakReference(Generator $result) : int
    {
        $count = 0;
        $this->storage = [];
        foreach ($result as $obj) {
            $this->storage[] = WeakReference::create($obj);
            $count++;
        }
        return $count;
    }
}
