<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Lodger;
use Faker\Factory;
use Monolog\DateTimeImmutable;


class LodgerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $Lodger = new Lodger();
            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
            $Lodger->setName($faker->name);
            $Lodger->setFirstname($faker->firstName);
            $Lodger->setAddress($faker->address);
            $Lodger->setPhone($faker->phoneNumber);
            $Lodger->setMail($faker->email());
            $Lodger->setDateOfBirth(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 years', '-18 years')));
            $Lodger->setJob($faker->jobTitle);
            $Lodger->setSalary($faker->randomFloat(1, 1300, 3000));
            $Lodger->setSex($faker->randomElement(['Homme', 'Femme']));

            $manager->persist($Lodger);
        }
        $manager->flush();
    }
}
