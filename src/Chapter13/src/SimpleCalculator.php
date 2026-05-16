<?php

namespace Cookbook\Chapter13\src;

use InvalidArgumentException;

final class SimpleCalculator implements SimpleCalculatorInterface
{
    public function calculate(
        float $firstNumber,
        float $secondNumber,
        CalculatorOperation $operation
    ): float {
        return match ($operation) {
            CalculatorOperation::ADD => $firstNumber + $secondNumber,
            CalculatorOperation::MULTIPLY => $firstNumber * $secondNumber,
            CalculatorOperation::DIVIDE => $this->handleDivision($firstNumber, $secondNumber),
            default => throw new InvalidArgumentException(
                sprintf(
                    'Operation "%s" is not yet supported.',
                    $operation->value
                )
            ),
        };
    }

    private function handleDivision(float $firstNumber, float $secondNumber): float
    {
        if ($secondNumber === 0.0) {
            throw new InvalidArgumentException('Division by zero is not allowed.');
        }

        return $firstNumber / $secondNumber;
    }
}