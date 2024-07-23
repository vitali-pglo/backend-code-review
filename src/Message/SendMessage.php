<?php
declare(strict_types=1);

namespace App\Message;

class SendMessage
{
    public function __construct(
        private readonly string $text,
    ) { }

    public function getText(): string
    {
        return $this->text;
    }
}