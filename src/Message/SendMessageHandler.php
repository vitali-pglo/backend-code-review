<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
/**
 * TODO: Cover with a test
 */
readonly class SendMessageHandler
{
    public function __construct(
        private EntityManagerInterface $manager,
    ) { }
    
    public function __invoke(SendMessage $sendMessage): void
    {
        $message = new Message();
        $message->setText($sendMessage->getText());
        $message->setStatus(MessageStatus::SENT);

        $this->manager->persist($message);
        $this->manager->flush();
    }
}