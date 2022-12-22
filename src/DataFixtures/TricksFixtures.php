<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\DataFixtures\UsersFixtures;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager) :void
    {
        for ($count = 0; $count < 15; $count++) {
            $trick = new Trick();
            $trick->setAuthor($this->getReference(UsersFixtures::USER_REFERENCE));
            $trick->setName('Trick ' . $count);
            $trick->setDescription('Lorem ipsum dolor sit amet ' . $count);
            $trick->setCreatedAt(new DateTimeImmutable());
            $trick->setUpdatedAt(new DateTimeImmutable());
            $trick->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE));
            $manager->persist($trick);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UsersFixtures::class,
            CategoryFixtures::class
        );
    }
}