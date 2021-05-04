<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{ const CATEGORY = [
    'Beauté',
    'Services',
    'Loisirs',
    'Sport',
    'Maison',
    'Amitie'
];
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORY as $key => $categoryname) {
            $category = new Category();
            $category->setTitre($categoryname);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
