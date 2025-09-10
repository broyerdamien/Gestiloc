<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Self_;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    const TYPE_EMPTY = 1;
    const TYPE_FURNISHED = 2;
    const TYPE_STUDENT = 3;
    const TYPE_SEASONAL = 4;
    const TYPE_GARAGE = 5;
    const TYPE_COMMERCIAL = 6;
    const TYPE_PROFESSIONAL = 7;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $Type = null;

    #[ORM\Column(nullable: true)]
    private ?int $Depot = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column]
    private ?bool $etat = null;

    #[ORM\ManyToMany(targetEntity: Property::class, inversedBy: 'locations')]
    private Collection $properties;

    #[ORM\ManyToMany(targetEntity: Lodger::class, inversedBy: 'locations')]
    private Collection $lodgers;
    private ?Lodger $lodger = null;

    #[ORM\Column(nullable: true)]
    private ?float $loyer = null;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: AvisEcheance::class)]
    private Collection $avisEcheances;

    public function getLodger(): ?Lodger
    {
        return $this->lodger;
    }

    public function setLodger(?Lodger $lodger): self
    {
        $this->lodger = $lodger;

        return $this;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->lodgers = new ArrayCollection();
        $this->avisEcheances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->Type;
    }

    public function setType(?int $Type): static
    {
        $this->Type = $Type;
        return $this;
    }


    public function getDepot(): ?int
    {
        return $this->Depot;
    }

    public function setDepot(?int $Depot): static
    {
        $this->Depot = $Depot;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): static
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
        }

        return $this;
    }

    public function removeProperty(Property $property): static
    {
        $this->properties->removeElement($property);

        return $this;
    }

    /**
     * @return Collection<int, Lodger>
     */
    public function getLodgers(): Collection
    {
        return $this->lodgers;
    }

    public function addLodger(Lodger $lodger): static
    {
        if (!$this->lodgers->contains($lodger)) {
            $this->lodgers->add($lodger);
        }

        return $this;
    }

    public function removeLodger(Lodger $lodger): static
    {
        $this->lodgers->removeElement($lodger);

        return $this;
    }

    public function getLoyer(): ?float
    {
        return $this->loyer;
    }

    public function setLoyer(?float $loyer): static
    {
        $this->loyer = $loyer;

        return $this;
    }

    /**
     * @return Collection<int, AvisEcheance>
     */
    public function getAvisEcheances(): Collection
    {
        return $this->avisEcheances;
    }

    public function addAvisEcheance(AvisEcheance $avisEcheance): static
    {
        if (!$this->avisEcheances->contains($avisEcheance)) {
            $this->avisEcheances->add($avisEcheance);
            $avisEcheance->setLocation($this);
        }

        return $this;
    }

    public function removeAvisEcheance(AvisEcheance $avisEcheance): static
    {
        if ($this->avisEcheances->removeElement($avisEcheance)) {
            // set the owning side to null (unless already changed)
            if ($avisEcheance->getLocation() === $this) {
                $avisEcheance->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * Return the possible choices for Type of Location.
     *
     * @return array
     */

    public static function getTypesChoices(): array
    {

        return [
            'location.form.types.empty' => self::TYPE_EMPTY,
            'location.form.types.furnished' => self::TYPE_FURNISHED,
            'location.form.types.student' => self::TYPE_STUDENT,
            'location.form.types.seasonal' => self::TYPE_SEASONAL,
            'location.form.types.garage' => self::TYPE_GARAGE,
            'location.form.types.commercial' => self::TYPE_COMMERCIAL,
            'location.form.types.professional' => self::TYPE_PROFESSIONAL,
        ];
    }

    /**
     * Get the label for the current Type.
     *
     * @return string|null
     */
    public function getLabelTypes(): ?string
    {
        $types = self::getTypesChoices();

        foreach ($types as $label => $value) {
            if ($value === $this->Type) {
                return $label;
            }
        }
        return null;
    }


}
