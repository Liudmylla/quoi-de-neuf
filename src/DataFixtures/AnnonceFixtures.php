<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Provider\Lorem;
use Faker\Provider\Address;
use Faker\Provider\Biased;

class AnnonceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 20; $i++) {
        $annonce = new Annonce();
        $annonce->setTitre($faker->name);
        $annonce->setContenu($faker->text);
        $annonce->setCreatedDate(new DateTime('now'));
        $annonce->setIsValidated(false);
        $annonce->setLieux($faker->city);
        $manager->persist($annonce);
        $manager->flush();
        }
       
    }
}
