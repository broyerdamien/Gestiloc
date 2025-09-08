<?php

namespace App\Service;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Carbon\Carbon;
use App\Entity\Location;
use App\Entity\AvisEcheance;
use App\Enum\PaymentStatus;
use http\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;

class AvisEcheanceService
{
    private EntityManagerInterface $entityManager;
    private PaymentRepository $paymentRepository;

    public function __construct(EntityManagerInterface $entityManager, PaymentRepository $paymentRepository)
    {
        $this->entityManager = $entityManager;
        $this->paymentRepository = $paymentRepository;
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

    public function addPaymentAvisEcheance(AvisEcheance $avisEcheance, float $paymentAmount): Payment
    {
        if ($avisEcheance->getPaymentStatus() !== PaymentStatus::PARTIEL) {
            throw new \InvalidArgumentException('Le paiement ne peut être ajouté que si le statut est PARTIEL.');
        }
        if ($paymentAmount <= 0) {
            throw new InvalidArgumentException('le montant du payment doit être superieur à 0');
        }

        $payment = new Payment();
        $payment->setAmount($paymentAmount);
        $payment->setPaymentDate(new \DateTimeImmutable());
        $payment->setAvisEcheance($avisEcheance);
        $avisEcheance->addPayment($payment);

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $this->updatePayementStatus($avisEcheance);
        return $payment;
    }

    public function updatePayementStatus(AvisEcheance $avisEcheance): void
    {
        $totalPaid = $this->paymentRepository->getTotalPaidForAvisEcheance($avisEcheance) ?? 0;
        $remainingAmount = $avisEcheance->getAmount() - $totalPaid;
        $avisEcheance->setRemainingAmount($remainingAmount);

        if ($remainingAmount < 0) {

            $excessAmount = abs($remainingAmount); // Convertir en positif
            $avisEcheance->setRemainingAmount(0);
            $avisEcheance->setExcessAmount($excessAmount);
        } else {
            $avisEcheance->setRemainingAmount($remainingAmount);
            $avisEcheance->setExcessAmount(0); // Réinitialiser l'excédent si tout est normal
        }
        if ($remainingAmount <= 0) {
            $avisEcheance->setPaymentStatus(PaymentStatus::PAYE);
        } else {
            $avisEcheance->setPaymentStatus(PaymentStatus::PARTIEL);
        }
        $this->entityManager->persist($avisEcheance);
        $this->entityManager->flush();
    }
    public function handlePartialPayment(AvisEcheance $avisEcheance, FormInterface $form): void
    {
        $partialPaymentAmount = $form->get('partialPaymentAmount')->getData();
        if ($partialPaymentAmount > 0) {
            $this->addPaymentAvisEcheance($avisEcheance, $partialPaymentAmount);
        }
    }
}
