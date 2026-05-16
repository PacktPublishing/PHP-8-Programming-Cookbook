<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

/**
 * @see simple_calculator.feature
 */
final class SimpleCalculatorTest extends TestCase
{
    /**
     * Scenario: Add two numbers using the plus operator *
     * @link simple_calculator.feature:8
     */
    public function test_add_two_numbers_using_plus_operator():
    void
    {
    // Given I have entered "5" into the first number field // And I have entered "3" into the second number field // And I have selected the "+" operator
    // When I submit the form
    // Then I should see "8" displayed as the result
        $this->fail("Test not implemented: implement calculator or business logic to make this pass.");
    }
}
