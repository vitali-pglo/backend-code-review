<?php

namespace App\Common\Response;

class InvalidRequest
{
    private int $status = 400;

    /**
     * @var string[]
     */
    private array $errors = [];

    /**
     * @return  string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string[] $errors
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

}