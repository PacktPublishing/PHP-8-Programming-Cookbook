<?php

namespace Cookbook\Chapter11\State;

class CoffeeMakerStateMachine
{
    private string $state;

    private array $transitions = [
        CoffeeMachineState::STANDBY => [
            CoffeeMachineEvent::START => CoffeeMachineState::HEATING,
        ],
        CoffeeMachineState::HEATING => [
            CoffeeMachineEvent::HEAT_COMPLETE => CoffeeMachineState::BREWING,
        ],
        CoffeeMachineState::BREWING => [
            CoffeeMachineEvent::BREW_COMPLETE => CoffeeMachineState::READY,
        ],
        CoffeeMachineState::READY => [
            CoffeeMachineEvent::SERVE => CoffeeMachineState::STANDBY,
        ],
    ];

    public function __construct()
    {
        $this->state = CoffeeMachineState::STANDBY;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function trigger(string $event): void
    {
        echo "=== Triggering Event: '{$event}' ===\n";
        echo "Current State: {$this->state}\n";

        if (isset($this->transitions[$this->state][$event])) {
            $newState = $this->transitions[$this->state][$event];
            echo "Transition allowed. Moving to state: {$newState}\n";
            $this->state = $newState;
        } else {
            echo "Error: Cannot trigger '{$event}' from state '{$this->state}'.\n";
        }

        echo "Updated State: {$this->state}\n\n";
    }
}