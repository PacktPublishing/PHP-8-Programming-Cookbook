<?php

namespace Cookbook\Chapter10\LinkedList;

class HiringNode extends AbstractNode
{
    public function __construct()
    {
        $this->setNodeType("I'm a Hiring Step");
    }
}