<?php

namespace Cookbook\Chapter06\Factory;

enum InputType: string
{
    case RADIO = 'radio';
    case SELECT = 'select';
    case TEXT = 'text';
    case EMAIL = 'email';
}