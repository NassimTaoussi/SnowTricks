<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Photo;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    public const TRICK_REFERENCE = '4';

    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($count = 0; $count < 60; ++$count) {
            $trick = new Trick();
            $trick->setAuthor($this->getReference(UsersFixtures::USER_REFERENCE));
            $trick->setName('Trick ' . $count);
            $trick->setDescription('Lorem ipsum dolor sit amet ' . $count);
            $trick->setCreatedAt(new \DateTimeImmutable());
            $trick->setUpdatedAt(new \DateTimeImmutable());
            $trick->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE));
            for ($i = 1; $i < 3; ++$i) {
                $photo = new Photo();
                $photo->setName('image.png');
                if (1 === $i) {
                    $photo->setCover(true);
                }
                $trick->addPhoto($photo);
            }
            $trick->setSlug($this->slugger->slug($trick->getName())->lower());
            $manager->persist($trick);
        }

        $this->addReference(self::TRICK_REFERENCE, $trick);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
