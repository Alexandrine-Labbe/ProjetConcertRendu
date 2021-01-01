<?php


namespace App\DataFixtures;


use App\Entity\Concert;
use App\Repository\BandRepository;
use App\Repository\HallRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ConcertFixtures extends Fixture implements DependentFixtureInterface
{

    private BandRepository $bandRepository;
    private HallRepository $hallRepository;

    /**
     * ConcertHallFixtures constructor.
     * @param BandRepository $bandRepository
     * @param HallRepository $hallRepository
     */
    public function __construct(BandRepository $bandRepository, HallRepository $hallRepository)
    {
        $this->bandRepository = $bandRepository;
        $this->hallRepository = $hallRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $nbShow2018 = 42;
        $nbShow2019 = 35;
        $nbShow2020 = 25;
        $nbShow2021 = 10;

        $hall_list = $this->hallRepository->findAll();
        $band_list = $this->bandRepository->findAll();

        for($i = 0; $i < $nbShow2018; $i++) {
            $show = new Concert();
            $show
                ->setBand($band_list[$faker->numberBetween(0, count($band_list) -1)])
                ->setDate($faker->dateTimeInInterval('-3 years', '+1 year'))
                ->setHall($hall_list[$faker->numberBetween(0, count($hall_list) -1)])
                ->setTourName($faker->word);

            $manager->persist($show);
        }

        for($i = 0; $i < $nbShow2019; $i++) {
            $show = new Concert();
            $show
                ->setBand($band_list[$faker->numberBetween(0, count($band_list) -1)])
                ->setDate($faker->dateTimeInInterval('-2 years', '+1 year'))
                ->setHall($hall_list[$faker->numberBetween(0, count($hall_list) -1)])
                ->setTourName($faker->word);

            $manager->persist($show);
        }

        for($i = 0; $i < $nbShow2020; $i++) {
            $show = new Concert();
            $show
                ->setBand($band_list[$faker->numberBetween(0, count($band_list) -1)])
                ->setDate($faker->dateTimeInInterval('-1 years', '+1 year'))
                ->setHall($hall_list[$faker->numberBetween(0, count($hall_list) -1)])
                ->setTourName($faker->word);

            $manager->persist($show);
        }

        for($i = 0; $i < $nbShow2020; $i++) {
            $show = new Concert();
            $show
                ->setBand($band_list[$faker->numberBetween(0, count($band_list) -1)])
                ->setDate($faker->dateTimeInInterval('now', '+6 month'))
                ->setHall($hall_list[$faker->numberBetween(0, count($hall_list) -1)])
                ->setTourName($faker->word);

            $manager->persist($show);
        }

        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            HallFixtures::class,
        ];

    }
}
