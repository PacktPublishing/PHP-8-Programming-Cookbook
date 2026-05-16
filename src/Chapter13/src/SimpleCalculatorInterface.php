<?php

namespace Cookbook\Chapter13\src;

interface SimpleCalculatorInterface
{
    public function calculate(
        float $firstNumber,
        float $secondNumber,
        CalculatorOperation $operation
    ): float;
}