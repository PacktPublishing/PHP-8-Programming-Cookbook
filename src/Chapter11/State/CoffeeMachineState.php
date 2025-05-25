<?php

namespace Cookbook\Chapter11\State;

class CoffeeMachineState
{
    public const STANDBY = 'standby';
    public const HEATING = 'heating';
    public const BREWING = 'brewing';
    public const READY = 'ready';
}