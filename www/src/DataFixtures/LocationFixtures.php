<?php
namespace App\DataFixtures;
use App\Entity\Location;
use App\Entity\Lodger;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LocationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $typesChoices = array_values(Location::getTypesChoices());
        for ($i = 0; $i < 10; $i++) {
            $location = new Location();
            $location->setType($faker->randomElement($typesChoices));
            $location->setDepot($faker->numberBetween(300, 1000));
            $location->setStartDate(new \DateTimeImmutable($faker->date()));
            $location->setEndDate(new \DateTimeImmutable($faker->date()));
            $location->setEtat($faker->boolean);
            $location->setLoyer($faker->randomFloat(2, 500, 3000));

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