<?php

namespace Cookbook\Chapter06\GenericInputForm;

enum InputType
{
    case Text;
    case Email;
    case Password;
    case Radio;
    case Select;
}