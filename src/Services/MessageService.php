<?php

namespace App\Services;

use App\Common\Response\PaginatedResponse;
use App\Message\MessageStatus;
use App\Message\SendMessage;
use App\Repository\MessageRepository;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageService
{
    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly  MessageBusInterface $bus,
    ) { }

    /**
     * @return PaginatedResponse
     */
    public function getPaginated(?MessageStatus $status, int $page = 1): PaginatedResponse
    {
        return $this->messageRepository->findPaginated($status, $page);
    }

    /**
     * @throws ExceptionInterface
     */
    public function send(string $text): void
    {
        $this->bus->dispatch(new SendMessage($text));
    }
}