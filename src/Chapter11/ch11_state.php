<?php

use Cookbook\Chapter11\State\CoffeeMakerStateMachine;
use Cookbook\Chapter11\State\CoffeeMachineEvent;

include __DIR__ . '/../../vendor/autoload.php';

$machine = new CoffeeMakerStateMachine();

$machine->trigger(CoffeeMachineEvent::START);
$machine->trigger(CoffeeMachineEvent::HEAT_COMPLETE);
$machine->trigger(CoffeeMachineEvent::BREW_COMPLETE);
$machine->trigger(CoffeeMachineEvent::SERVE);