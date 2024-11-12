<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Lodger;
#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $building = null;

    #[ORM\Column(nullable: true)]
    private ?int $etage = null;

    #[ORM\Column(nullable: true)]
    private ?int $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column]
    private ?int $postCode = null;

    #[ORM\Column(nullable: true)]
    private ?float $area = null;

    #[ORM\Column]
    private ?int $numberOfParts = null;

    #[ORM\Column(nullable: true)]
    private ?int $bedroom = null;

    #[ORM\Column(nullable: true)]
    private ?int $bathroom = null;


    #[ORM\Column(nullable: true)]
    private ?float $rentalCharges = null;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Lodger::class)]
    private Collection $lodgers;

    #[ORM\ManyToMany(targetEntity: Location::class, mappedBy: 'properties')]
    private Collection $locations;

    public function __construct()
    {
        $this->lodgers = new ArrayCollection();
        $this->locations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(?string $building): static
    {
        $this->building = $building;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(?int $etage): static
    {
        $this->etage = $etage;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPostCode(): ?int
    {
        return $this->postCode;
    }

    public function setPostCode(int $postCode): static
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(?float $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getNumberOfParts(): ?int
    {
        return $this->numberOfParts;
    }

    public function setNumberOfParts(int $numberOfParts): static
    {
        $this->numberOfParts = $numberOfParts;

        return $this;
    }

    public function getBedroom(): ?int
    {
        return $this->bedroom;
    }

    public function setBedroom(?int $bedroom): static
    {
        $this->bedroom = $bedroom;

        return $this;
    }

    public function getBathroom(): ?int
    {
        return $this->bathroom;
    }

    public function setBathroom(?int $bathroom): static
    {
        $this->bathroom = $bathroom;

        return $this;
    }

    public function getRentalCharges(): ?float
    {
        return $this->rentalCharges;
    }

    public function setRentalCharges(?float $rentalCharges): static
    {
        $this->rentalCharges = $rentalCharges;

        return $this;
    }

    /**
     * @return Collection<int, Lodger>
     */
    public function getLodgers(): Collection
    {
        return $this->lodgers;
    }

    public function addLodger(Lodger $lodger): self
    {
        if (!$this->lodgers->contains($lodger)) {
            $this->lodgers[] = $lodger;
            $lodger->setProperty($this); // Assurez-vous de définir la propriété dans Lodger
        }

        return $this;
    }

    public function removeLodger(Lodger $lodger): self
    {
        if ($this->lodgers->removeElement($lodger)) {
            if ($lodger->getProperty() === $this) {
                $lodger->setProperty(null); // Enlevez la propriété si elle est déjà définie
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->addProperty($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            $location->removeProperty($this);
        }

        return $this;
    }

}