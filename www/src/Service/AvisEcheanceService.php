<?php

namespace App\Service;

use App\Entity\AvisEcheance;
use App\Entity\Location;
use App\Entity\Payment;
use App\Enum\PaymentStatus;
use App\Repository\PaymentRepository;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * Générer un avis d'échéance pour une location donnée
     */
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

    /**
     * Générer des avis d’échéance pour toutes les locations
     */
    public function generateAllAvisEcheances(): array
    {
        $avisEcheances = [];
        $locations = $this->entityManager->getRepository(Location::class)->findAll();

        foreach ($locations as $location) {
            $avisEcheances[] = $this->generateOneAvisEcheance($location);
        }

        return $avisEcheances;
    }

    /**
     * Supprimer un avis d’échéance
     */
    public function deleteAvisEcheance(AvisEcheance $avisEcheance): void
    {
        $this->entityManager->remove($avisEcheance);
        $this->entityManager->flush();
    }

    /**
     * Supprimer plusieurs avis d’échéance
     */
    public function deleteSelectedAvisEcheances(array $selectedIds): void
    {
        $avisEcheances = $this->entityManager->getRepository(AvisEcheance::class)->findBy(['id' => $selectedIds]);

        foreach ($avisEcheances as $avisEcheance) {
            $this->entityManager->remove($avisEcheance);
        }

        $this->entityManager->flush();
    }

    /**
     * Ajouter un paiement partiel sur un avis d’échéance
     */
    public function addPaymentAvisEcheance(AvisEcheance $avisEcheance, float $paymentAmount): Payment
    {
        if ($paymentAmount <= 0) {
            throw new \InvalidArgumentException('Le montant du paiement doit être supérieur à 0.');
        }

        $payment = new Payment();
        $payment->setAmount($paymentAmount);
        $payment->setPaymentDate(new \DateTimeImmutable());
        $payment->setAvisEcheance($avisEcheance);
        $avisEcheance->addPayment($payment);

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $this->updatePaymentStatus($avisEcheance);

        return $payment;
    }

    /**
     * Mettre à jour le statut de paiement d’un avis d’échéance
     */
    public function updatePaymentStatus(AvisEcheance $avisEcheance): void
    {
        $totalPaid = $this->paymentRepository->getTotalPaidForAvisEcheance($avisEcheance) ?? 0;
        $remainingAmount = $avisEcheance->getAmount() - $totalPaid;

        if ($remainingAmount < 0) {
            $avisEcheance->setRemainingAmount(0);
            $avisEcheance->setExcessAmount(abs($remainingAmount));
        } else {
            $avisEcheance->setRemainingAmount($remainingAmount);
            $avisEcheance->setExcessAmount(0);
        }

        if ($remainingAmount <= 0) {
            $avisEcheance->setPaymentStatus(PaymentStatus::PAYE);
        } elseif ($remainingAmount < $avisEcheance->getAmount()) {
            $avisEcheance->setPaymentStatus(PaymentStatus::PARTIEL);
        } else {
            $avisEcheance->setPaymentStatus(PaymentStatus::EN_ATTENTE);
        }

        $this->entityManager->persist($avisEcheance);
        $this->entityManager->flush();
    }

    /**
     * Gérer un paiement partiel depuis un formulaire
     */
    public function handlePartialPayment(AvisEcheance $avisEcheance, FormInterface $form): void
    {
        $partialPaymentAmount = $form->get('partialPaymentAmount')->getData();

        if ($partialPaymentAmount > 0) {
            $this->addPaymentAvisEcheance($avisEcheance, $partialPaymentAmount);
        }
    }

    /**
     * Changer le statut de paiement (sans ajouter de paiement)
     */
    public function changePaymentStatus(AvisEcheance $avisEcheance, PaymentStatus $status): void
    {
        $avisEcheance->setPaymentStatus($status);
        $this->entityManager->flush();
    }
}
