<?php

namespace App\Message;

enum MessageStatus: int
{
    case PENDING = 1;
    case SENT = 2;
    case DELIVERED = 3;
    case READ = 4;
}
