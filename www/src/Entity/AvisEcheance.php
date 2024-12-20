<?php

namespace App\Entity;

use App\Repository\AvisEcheanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\PaymentStatus;
#[ORM\Entity(repositoryClass: AvisEcheanceRepository::class)]
class AvisEcheance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column(type: 'string', length: 20, enumType: PaymentStatus::class)]
    private PaymentStatus $paymentStatus = PaymentStatus::EN_ATTENTE;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'avisEcheances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\OneToMany(mappedBy: 'avisEcheance', targetEntity: Payment::class)]
    private Collection $payments;

    #[ORM\Column(nullable: true)]
    private ?float $remainingAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $excessAmount = 0;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->remainingAmount =$this->amount;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPaymentStatus(): PaymentStatus
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(PaymentStatus $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;
        $this->remainingAmount = $amount;
        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setAvisEcheance($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getAvisEcheance() === $this) {
                $payment->setAvisEcheance(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        // Retourne une chaîne de caractères représentant l'avis d'échéance
        return sprintf('Avis ID %d - Date début : %s - Montant : %.2f€',
            $this->id,
            $this->dateDebut->format('Y-m-d'),
            $this->amount
        );
    }

    public function getRemainingAmount(): ?float
    {
        return $this->remainingAmount;
    }

    public function setRemainingAmount(?float $remainingAmount): static
    {
        $this->remainingAmount = $remainingAmount;

        return $this;
    }

    public function getExcessAmount(): ?float
    {
        return $this->excessAmount;
    }

    public function setExcessAmount(?float $excessAmount): static
    {
        $this->excessAmount = $excessAmount;

        return $this;
    }

}
