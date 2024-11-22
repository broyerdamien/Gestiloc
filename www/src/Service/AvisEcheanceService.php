<?php

namespace App\Service;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Carbon\Carbon;
use App\Entity\Location;
use App\Entity\AvisEcheance;
use App\Enum\PaymentStatus;

class AvisEcheanceService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateOneAvisEcheance(Location $location): AvisEcheance
    {
        $avisEcheance = new AvisEcheance();
        $avisEcheance->setDateDebut(CarbonImmutable::now()->startOfMonth());
        $avisEcheance->setDateFin(CarbonImmutable::now()->endOfMonth());
        $avisEcheance->setPaymentStatus(PaymentStatus::EN_ATTENTE);
        $avisEcheance->setAmount($location->getLoyer());
        $avisEcheance->setLocation($location);
        $this->entityManager->persist($avisEcheance);
        $this->entityManager->flush();

        return $avisEcheance;
    }

    public function generateAllAvisEcheances(): array
    {
        $avisEcheances = [];
        $locations = $this->entityManager->getRepository(Location::class)->findAll();
        foreach ($locations as $location) {
            $avisEcheances[] = $this->generateOneAvisEcheance($location);
        }
        return $avisEcheances;
    }

    public function deleteSelectedAvisEcheances(array $selectedIds): void
    {
        $avisEcheances = $this->entityManager->getRepository(AvisEcheance::class)->findBy(['id' => $selectedIds]);

        foreach ($avisEcheances as $avisEcheance) {
            $this->entityManager->remove($avisEcheance);
        }

        $this->entityManager->flush();
    }
}