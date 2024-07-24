<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Message\MessageStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;
use function Psl\Iter\random;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        foreach (range(1, 10) as $i) {
            $message = new Message();
            $message->setText($faker->sentence);
            $message->setStatus(random([MessageStatus::SENT, MessageStatus::READ]));
            
            $manager->persist($message);
        }

        $manager->flush();
    }
}
