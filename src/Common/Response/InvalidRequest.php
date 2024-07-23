<?php

namespace App\Common\Response;

class InvalidRequest
{
    private int $status = 400;
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

}