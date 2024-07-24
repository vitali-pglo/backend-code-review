<?php

namespace App\Message;

enum MessageStatus: int
{
    case SENT = 1;
    case READ = 2;
}
