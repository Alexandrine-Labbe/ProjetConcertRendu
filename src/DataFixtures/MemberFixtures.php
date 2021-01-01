<?php

namespace App\DataFixtures;

use App\Entity\Member;
use App\Repository\BandRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Provider\fr_FR\Address;

class MemberFixtures extends Fixture implements DependentFixtureInterface
{

    private BandRepository $bandRepository;

    /**
     * ConcertHallFixtures constructor.
     * @param BandRepository $bandRepository
     */
    public function __construct(BandRepository $bandRepository)
    {
        $this->bandRepository = $bandRepository;
    }


    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $faker->addProvider(new Address($faker));
        $nbMember = 50;

        $band_list = $this->bandRepository->findAll();

        for($i = 0; $i < $nbMember; $i++) {
            $member = new Member();
            $member
                ->setName($faker->lastName)
                ->setBirthDate($faker->dateTimeBetween('-50 years', '-15 years'))
                ->setFirstName($faker->firstName)
                ->setJob($faker->jobTitle)
                ->setPicturePath("uploads/images/" . self::getRandomPicture());

            $band_list[$faker->numberBetween(0, count($band_list) -1)]->addMember($member);

            $manager->persist($member);
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


    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            BandFixtures::class,
        ];

    }

}
