<?php

namespace App\Common\Response;

use Doctrine\ORM\Mapping\Entity;

class PaginatedResponse
{
    private int $total;

    /**
     * @var mixed[]
     */
    private array $data;

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param mixed[] $data
     * @return void
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}