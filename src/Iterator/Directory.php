<?php
namespace Cookbook\Iterator;

use Iterator;
use Generator;
use InvalidArgumentException;
use RegexIterator;
use RecursiveRegexIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Directory
{      
    const ERROR_UNABLE = 'ERROR: Unable to read directory';
    protected Iterator $rdi;

    // set up recursive directory iterator
    public function __construct(public string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(static::ERROR_UNABLE);
        }
        $this->rdi = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::SELF_FIRST
        );
    }

    // applies regex to filter the iteration
    protected function regex($iterator, $pattern)
    {
        $pattern = '!^.' . str_replace('.', '\\.', $pattern) . '$!';
        return new RegexIterator($iterator, $pattern);
    }

    // mimics Linux "ls -l"
    public function ls(string $pattern = '') : Generator
    {
        $outerIterator = (!empty($pattern)) 
        ? $this->regex($this->rdi, $pattern) 
        : $this->rdi;
        foreach($outerIterator as $obj){
            if ($obj->isDir()) {
                if ($obj->getFileName() == '..') continue;
                $line = $obj->getPath() . PHP_EOL;
            } else {
                $line = sprintf(
                    '%4s %1d %4s %4s %10d %12s %-40s',
                    substr(sprintf('%o', $obj->getPerms()), -4),
                    ($obj->getType() == 'file') ? 1 : 2,
                    $obj->getOwner(),
                    $obj->getGroup(),
                    $obj->getSize(),
                    date('M d Y H:i', $obj->getATime()),
                    $obj->getFileName()
                );
            }
            yield $line;
        }
    }

    // mimics DOS "dir /s"
    public function dir(string $pattern = '') : Generator
    {
        $outerIterator = (!empty($pattern))
                       ? $this->regex($this->rdi, $pattern) 
                       : $this->rdi;
        foreach($outerIterator as $name => $obj){
            yield $name;
        }        
    }
}
