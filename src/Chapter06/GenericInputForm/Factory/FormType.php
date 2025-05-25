<?php

namespace Cookbook\Chapter06\GenericInputForm\Factory;

enum FormType: string
{
    case LOGIN = 'login';
    case REGISTER = 'register';
}
