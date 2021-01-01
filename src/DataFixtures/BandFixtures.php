<?php


namespace App\DataFixtures;

use App\Entity\Band;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Image;

class BandFixtures extends Fixture
{



    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $nbBand = 10;


        for ($i = 0; $i < $nbBand; $i++) {
            $band = new Band();
            $band
                ->setName($faker->word)
                ->setLastAlbumName($faker->word)
                ->setStyle($faker->word)
                ->setYearOfCreation($faker->year)
                ->setPicturePath("uploads/images/" . self::getRandomPicture());

            $manager->persist($band);

        }

        $manager->flush();

    }

    function getRandomPicture()
    {
        $faker = Factory::create();

        return $faker->randomElement([
            "bow.png",
            "catra.jpeg",
            "doubletrouble.jpeg",
            "entrapta.jpeg",
            "glimmer.jpeg",
            "scorpia.jpeg",
            "seahawk.png",
            "she-ra.jpg"
        ]);
    }
}
