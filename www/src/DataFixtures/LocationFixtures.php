<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Location;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LocationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $typeBail = ['Bail d\'habitation vide',
            'Bail d\'habitation meublé',
            'Bail meublé étudiant',
            'bail location saisonière',
            'bail parking/garage',
            'bail commercial',
            'bail Professionnel'];

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $location = new Location();
            $location->setType($faker->randomElement($typeBail));
            $location->setDepot($faker->numberBetween(300, 1000));
            $location->setStartDate(new \DateTimeImmutable($faker->date()));
            $location->setEndDate(new \DateTimeImmutable($faker->date()));
            $location->setEtat($faker->boolean);

            // Récupérer des Lodger et Property aléatoires
            $lodger = $this->getReference(LodgerFixtures::LODGERS_REFERENCE . rand(0, 9));
            $property = $this->getReference(PropertyFixtures::PROPERTY_REFERENCE . rand(0, 9));

            $location->addLodger($lodger);
            $location->addProperty($property);

            $manager->persist($location);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PropertyFixtures::class,
            LodgerFixtures::class,
        ];
    }
}
