<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'Flip';

    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Flip');
        $manager->persist($category);

        $this->addReference(self::CATEGORY_REFERENCE, $category);
        $manager->flush();
    }
}
