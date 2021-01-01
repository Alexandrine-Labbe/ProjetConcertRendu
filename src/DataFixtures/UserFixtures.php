<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $nbUsers = 5;
        $nbAdmin = 2;

        for($i = 0; $i < $nbUsers; $i++) {
            $user = new User();
            $user
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail("user" . ($i + 1) . '@exemple.com')
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'password'
                ))
                ;

            $manager->persist($user);
        }

        for($i = 0; $i < $nbAdmin; $i++) {
            $user = new User();
            $user
                ->setFirstName($faker->word)
                ->setLastName($faker->word)
                ->setEmail("admin" . ($i + 1) . '@exemple.com')
                ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'password'
                ))
            ;

            $manager->persist($user);
        }


        $manager->flush();
    }
}
