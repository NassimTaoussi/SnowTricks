<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public const USER_REFERENCE = 'user-gary';

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($count = 0; $count < 20; ++$count) {
            $user = new User();
            $user->setUsername('Username ' . $count);
            $user->setEmail('username' . $count . '@email.com');
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
            $user->isVerified(true);
            $manager->persist($user);
        }

        $this->addReference(self::USER_REFERENCE, $user);
        $manager->flush();
    }
}
