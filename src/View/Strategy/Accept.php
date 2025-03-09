<?php
namespace Cookbook\View\Strategy;

enum Accept : string
{
    case X = 'application/xml';
    case J = 'application/json';
    case H = 'text/html';
    case T = 'text/plain';
}
