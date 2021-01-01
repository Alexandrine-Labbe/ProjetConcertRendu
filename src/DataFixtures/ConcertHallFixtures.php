<?php

namespace App\DataFixtures;

use App\Entity\ConcertHall;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\fr_FR\Address;

class ConcertHallFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $faker->addProvider(new Address($faker));
        $nbConcertHall = 5;

        for($i = 0; $i < $nbConcertHall; $i++) {
            $concertHall = new ConcertHall();
            $concertHall
                ->setCity($faker->city)
                ->setName($faker->word)
                ->setPresentation($faker->paragraph(3,true))
                ->setTotalPlaces($faker->numberBetween(0, 5000));

            $manager->persist($concertHall);
        }


        $manager->flush();
    }
}
