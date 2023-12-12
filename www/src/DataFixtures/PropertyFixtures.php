<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Monolog\DateTimeImmutable;

class PropertyFixtures extends Fixture

{
    public const PROPERTY_REFERENCE = 'property';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $property = new property();
            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
            $property->setType($faker->randomElement(['Villa', 'Appartement', 'garage','place de parking']));
            $property->setName($faker->lastName);
            $property->setAddress($faker->address);
            $property->setBuilding($faker->name);
            $property->setEtage($faker->randomNumber(1,10));
            $property->setNumero($faker->randomNumber(1,10));
            $property->setCountry($faker->city);
            $property->setPostCode($faker->numerify('#####'));
            $property->setArea($faker->randomFloat(2, 1, 300));
            $property->setNumberOfParts($faker->randomNumber(1,10));
            $property->setBedroom($faker->randomNumber(1,4));
            $property->setBathroom($faker->randomNumber(1,4));
            $property->setLoyer($faker->randomFloat(2, 1, 3000));
            $property->setRentalCharges($faker->randomFloat(2, 1, 800));


            $manager->persist($property);
            $this->addReference(self::PROPERTY_REFERENCE . $i, $property);
        }
        $manager->flush();
    }
}
