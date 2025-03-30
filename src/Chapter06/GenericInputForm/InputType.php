<?php

namespace Cookbook\Chapter06\GenericInputForm;

enum InputType
{
    case Form;
    case Text;
    case Email;
    case Password;
}