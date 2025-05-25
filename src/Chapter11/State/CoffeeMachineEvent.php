<?php

namespace Cookbook\Chapter11\State;

class CoffeeMachineEvent
{
    public const START = 'start';
    public const HEAT_COMPLETE = 'heat_complete';
    public const BREW_COMPLETE = 'brew_complete';
    public const SERVE = 'serve';
}