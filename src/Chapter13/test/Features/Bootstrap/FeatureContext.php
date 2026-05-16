<?php

namespace Cookbook\Chapter13\test\Features\Bootstrap;

use Behat\Behat\Context\Context;
use Cookbook\Chapter13\src\CalculatorOperation;
use Cookbook\Chapter13\src\SimpleCalculator;
use Cookbook\Chapter13\src\SimpleCalculatorInterface;
use RuntimeException;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private float $firstNumber = 0.0; // Initialize default value
    private float $secondNumber = 0.0; // Initialize default value
    private ?CalculatorOperation $operator = null; // Nullable CalculatorOperation
    private ?float $result = null; // Result
    private SimpleCalculatorInterface $calculator;

    /**
     * Initializes context with a SimpleCalculator.
     */
    public function __construct()
    {
        $this->calculator = new SimpleCalculator();
    }

    #[Given('I am on the calculator page')]
    public function iAmOnTheCalculatorPage(): void
    {
        // This could be used for UI initialization, no operation needed for now.
    }

    #[Given('I have entered :arg1 into the first number field')]
    public function iHaveEnteredIntoTheFirstNumberField($arg1): void
    {
        // Convert input to float and save to firstNumber
        $this->firstNumber = (float) $arg1;
    }

    #[Given('I have entered :arg1 into the second number field')]
    public function iHaveEnteredIntoTheSecondNumberField($arg1): void
    {
        // Convert input to float and save to secondNumber
        $this->secondNumber = (float) $arg1;
    }

    #[Given('I have selected the :arg1 operator')]
    public function iHaveSelectedTheOperator($arg1): void
    {
        // Map the operator to CalculatorOperation enum
        $this->operator = match ($arg1) {
            '+' => CalculatorOperation::ADD,
            '-' => CalculatorOperation::SUBTRACT,
            '*' => CalculatorOperation::MULTIPLY,
            '/' => CalculatorOperation::DIVIDE,
            default => throw new RuntimeException(sprintf('Unsupported operator "%s".', $arg1)),
        };
    }

    #[Given('I have entered :arg1 into the second number field And I have selected the :arg2 operator')]
    public function iHaveEnteredIntoTheSecondNumberFieldAndIHaveSelectedTheOperator($arg1, $arg2): void
    {
        // Set the second number
        $this->secondNumber = (float) $arg1;

        // Map the operator to CalculatorOperation enum
        $this->operator = match ($arg2) {
            '+' => CalculatorOperation::ADD,
            '-' => CalculatorOperation::SUBTRACT,
            '*' => CalculatorOperation::MULTIPLY,
            '/' => CalculatorOperation::DIVIDE,
            default => throw new RuntimeException(sprintf('Unsupported operator "%s".', $arg2)),
        };
    }

    #[When('I submit the form')]
    public function iSubmitTheForm(): void
    {
        // Ensure operator is set
        if ($this->operator === null) {
            throw new RuntimeException('Operator is not set.');
        }

        // Perform the calculation using the SimpleCalculator
        $this->result = $this->calculator->calculate($this->firstNumber, $this->secondNumber, $this->operator);
    }

    #[Then('I should see :arg1 displayed as the result')]
    public function iShouldSeeDisplayedAsTheResult($arg1): void
    {
        // Convert argument to float for comparison
        $expected = (float) $arg1;

        // Ensure the result is computed
        if ($this->result === null) {
            throw new RuntimeException('The result has not been calculated yet.');
        }

        // Check if the calculated result matches the expected result
        if (abs($expected - $this->result) > 0.0001) { // Allow minor floating-point differences
            throw new RuntimeException(
                sprintf('Expected result is %.2f, but got %.2f.', $expected, $this->result)
            );
        }
    }
}