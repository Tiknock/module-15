<?php

namespace App\DataFixtures;

use App\Entity\Prestation;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $prestation = new Prestation;
            $prestation->setNom($faker->jobTitle());
            $prestation->setExtrait($faker->realText(80));
            $prestation->setDescription($faker->text());
            $prestation->setRemuneration($faker->buildingNumber());
            $prestation->setDateCreation($faker->dateTime());
            $prestation->setNumeroTelephone($faker->phoneNumber());
            $manager->persist($prestation);
        }

        $user = new User();
        $user->setEmail("user@gmail.com");
        $user->setNom($faker->name());
        $user->setPrenom($faker->firstName());
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "123"));
        $user->setDateInscription($faker->dateTime());
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);

        $manager->flush();
    }
}
