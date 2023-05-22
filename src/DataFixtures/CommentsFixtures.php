<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($count = 0; $count < 20; ++$count) {
            $comment = new Comment();
            $comment->setAuthor($this->getReference(UsersFixtures::USER_REFERENCE));
            $comment->setMessage('Lorem ipsum dolor sit amet ' . $count);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setTrick($this->getReference(TricksFixtures::TRICK_REFERENCE));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
            TricksFixtures::class,
        ];
    }
}
