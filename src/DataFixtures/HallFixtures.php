<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Repository\ConcertHallRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\fr_FR\Address;

class HallFixtures extends Fixture implements DependentFixtureInterface
{

    private ConcertHallRepository $concertHallRepository;

    /**
     * ConcertHallFixtures constructor.
     * @param ConcertHallRepository $concertHallRepository
     */
    public function __construct(ConcertHallRepository $concertHallRepository)
    {
        $this->concertHallRepository = $concertHallRepository;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $listHall = $this->concertHallRepository->findAll();
        $faker->addProvider(new Address($faker));
        $nbHall = 20;

        for($i = 0; $i < $nbHall; $i++) {
            $hall = new Hall();
            $hall
                ->setName($faker->word)
                ->setCapacity($faker->numberBetween(0, 5000))
                ->setAvailable($faker->boolean(75))
                ->setConcertHall(self::getConcertHall($listHall));

            $manager->persist($hall);
        }


        $manager->flush();
    }

    private function getConcertHall($list)
    {
        return $list[rand(0, count($list) - 1)];
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [ConcertHallFixtures::class];

    }
}
