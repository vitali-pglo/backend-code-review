<?php

namespace App\Tests\Message;

use App\Entity\Message;
use App\Message\MessageStatus;
use App\Message\SendMessage;
use App\Message\SendMessageHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SendMessageHandlerTest extends TestCase
{
    private EntityManagerInterface $manager;
    private SendMessageHandler $handler;

    protected function setUp(): void
    {
        $this->manager = $this->createMock(EntityManagerInterface::class);
        $this->handler = new SendMessageHandler($this->manager);
    }

    public function testInvoke(): void
    {
        $sendMessage = $this->createMock(SendMessage::class);
        $sendMessage->method('getText')->willReturn('Test message');

        $message = new Message();
        $message->setText('Test message');
        $message->setStatus(MessageStatus::SENT);

        $this->manager
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($arg) {
                return $arg instanceof Message
                    && $arg->getText() === 'Test message'
                    && $arg->getStatus() === MessageStatus::SENT;
            }));

        $this->manager
            ->expects($this->once())
            ->method('flush');

        $this->handler->__invoke($sendMessage);
    }
}
