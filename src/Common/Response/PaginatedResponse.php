<?php

namespace App\Common\Response;

use Doctrine\ORM\Mapping\Entity;

class PaginatedResponse
{
    private int $total;

    /**
     * @var Entity[]
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
     * @return Entity[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param Entity[] $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}