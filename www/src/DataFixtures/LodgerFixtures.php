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
    public const LODGERS_REFERENCE = 'lodgers';
    public function load(ObjectManager $manager): void
    {
        $noLodger = new Lodger();
        $noLodger->setName("Aucun locataire");

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $Lodger = new Lodger();
            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
            $Lodger->setName($faker->lastName);
            $Lodger->setFirstname($faker->firstName);
            $Lodger->setAddress($faker->streetName);
            $Lodger->setPhone($this->formatPhoneNumber($faker->phoneNumber));
            $Lodger->setMail($faker->email());
            $Lodger->setDateOfBirth(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 years', '-18 years')));
            $Lodger->setJob($faker->jobTitle);
            $Lodger->setSalary($faker->randomFloat(1, 1300, 3000));
            $Lodger->setSex($faker->randomElement(['Homme', 'Femme']));
            $Lodger->setProperty($this->getReference(PropertyFixtures::PROPERTY_REFERENCE . rand(0, 9)));

            $manager->persist($Lodger);
            $this->addReference(self::LODGERS_REFERENCE . $i, $Lodger);

        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            PropertyFixtures::class,
        ];
    }
    private function formatPhoneNumber($phone)
    {
        // Supprime tous les caractères non numériques
        $phone = preg_replace('/\D+/', '', $phone);

        // Assure que le numéro est exactement de 10 chiffres
        $phone = substr($phone, 0, 10);

        // Formate le numéro en ajoutant des espaces tous les deux chiffres
        return preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $phone);
    }

}
