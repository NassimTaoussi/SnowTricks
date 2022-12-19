<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public const USER_REFERENCE = 'user-gary';

    public function load(ObjectManager $manager): void
    {

        for ($count = 0; $count < 20; $count++) {
            $user = new User;
            $user->setUsername("Username " . $count);
            $user->setEmail("username" . $count . "@email.com");
            $user->setPassword("password");
            $user->isVerified(true);
            $manager->persist($user);
        }

        $this->addReference(self::USER_REFERENCE, $user);
        $manager->flush();
    }
}