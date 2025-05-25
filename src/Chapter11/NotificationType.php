<?php

namespace Cookbook\Chapter11;

use Cookbook\Chapter11\Medium\EmailNotification;
use Cookbook\Chapter11\Medium\SmsNotification;

enum NotificationType: string
{
    case EMAIL = EmailNotification::class;
    case SMS = SmsNotification::class;
}
