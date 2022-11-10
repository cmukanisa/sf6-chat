<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Thread;
use App\Entity\Message;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        Private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = (new User())
                ->setEmail("email$i@sf6chat.com")
                ->setRoles(['ROLE_USER'])
                ->setFirstname($faker->firstNameMale())
                ->setLastname($faker->lastName())
                ->setUsername(uniqid());

            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                "password"
            ));

            $users[] = $user;
            $manager->persist($user);
        }

        $threads = [];

        for ($i = 0; $i < 5; $i++) {
            $thread = (new Thread())->setSubject($faker->name())
                ->addParticipant($users[0])
                ->addParticipant($users[$i+1])
                ->addMessage((new Message())
                    ->setSender($users[0])
                    ->setSenderText(ucfirst($users[0]->getFirstname()).' '.strtoupper($users[0]->getLastname()))
                    ->setContent($faker->text(rand(20,100)))
                )->addMessage((new Message())
                    ->setSender($users[0])
                    ->setSenderText(ucfirst($users[0]->getFirstname()).' '.strtoupper($users[0]->getLastname()))
                    ->setContent($faker->text(rand(20,100)))
                )
                ->addMessage((new Message())
                    ->setSender($users[$i+1])
                    ->setSenderText(ucfirst($users[$i+1]->getFirstname()).' '.strtoupper($users[$i+1]->getLastname()))
                    ->setContent($faker->text(rand(20,100)))
                )
            ;

            $manager->persist($thread);
            $threads[] = $thread;
        }

        $manager->flush();
    }
}
