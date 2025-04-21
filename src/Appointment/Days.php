<?php
namespace Cookbook\Appointment;

enum Days : string
{
    case SUN = 'Sunday';
    case MON = 'Monday';
    case TUE = 'Tuesday';
    case WED = 'Wednesday';
    case THU = 'Thursday';
    case FRI = 'Friday';
    case SAT = 'Saturday';
}
