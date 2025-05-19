<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $admin = new User();
        $admin->setFirstName('Jordan')
            ->setLastName('Berti')
            ->setEmail('berti@epse.be')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'password'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        for($t=1; $t<=10; $t++)
        {
            $team = new Team();
            $team->setName($faker->name())
                ->setLogo("https://upload.wikimedia.org/wikipedia/fr/thumb/d/d1/Bulls_de_Chicago_logo.svg/langfr-180px-Bulls_de_Chicago_logo.svg.png");

            $manager->persist($team);

            for($p=1; $p<=10; $p++)
            {
                $player = new Player();
                $player->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setBirthday($faker->dateTimeBetween("-25 years"))
                    ->setNumber(rand(1,99))
                    ->setPicture("https://upload.wikimedia.org/wikipedia/commons/c/c3/Jordan_by_Lipofsky_16577.jpg?20070306092610")
                    ->setTeam($team);

                $manager->persist($player);
            }

        }

        $manager->flush();
    }
}
