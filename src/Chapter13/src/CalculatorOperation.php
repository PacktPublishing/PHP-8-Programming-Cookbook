<?php

namespace Cookbook\Chapter13\src;

enum CalculatorOperation: string
{
    case ADD = '+';
    case SUBTRACT = '-';
    case MULTIPLY = '*';
    case DIVIDE = '/';
}
