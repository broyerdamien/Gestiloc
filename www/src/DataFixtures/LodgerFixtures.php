<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Lodger;
use Faker\Factory;
use Monolog\DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LodgerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $Lodger = new Lodger();
            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
            $Lodger->setName($faker->lastName);
            $Lodger->setFirstname($faker->firstName);
            $Lodger->setAddress($faker->address);
            $Lodger->setPhone($faker->phoneNumber);
            $Lodger->setMail($faker->email());
            $Lodger->setDateOfBirth(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 years', '-18 years')));
            $Lodger->setJob($faker->jobTitle);
            $Lodger->setSalary($faker->randomFloat(1, 1300, 3000));
            $Lodger->setSex($faker->randomElement(['Homme', 'Femme']));
            $Lodger->setProperty($this->getReference(PropertyFixtures::PROPERTY_REFERENCE . rand(0, 9)));

            $manager->persist($Lodger);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            PropertyFixtures::class,
        ];
    }
}
