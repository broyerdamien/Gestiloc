<?php

namespace App\Controller;

use App\Entity\AvisEcheance;
use App\Entity\Payment;
use App\Enum\PaymentStatus;
use App\Form\AvisEcheanceType;
use App\Repository\AvisEcheanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AvisEcheanceService;


#[Route('/avis/echeance')]
class AvisEcheanceController extends AbstractController
{
    #[Route('/', name: 'app_avis_echeance_index', methods: ['GET'])]
    public function index(AvisEcheanceRepository $avisEcheanceRepository): Response
    {
        $paymentStatuses = PaymentStatus::cases();

        return $this->render('avis_echeance/index.html.twig', [
            'avis_echeances' => $avisEcheanceRepository->findAll(),
            'payment_statuses' => $paymentStatuses,
        ]);
    }

    #[Route('/generate', name: 'app_avis_echeance_generate', methods: ['GET'])]
    public function generateAvisEcheances(AvisEcheanceRepository $avisEcheanceRepository, AvisEcheanceService $avisEcheanceService): Response
    {

        return $this->render('avis_echeance/index.html.twig', [
            'avis_echeances' => $avisEcheanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_avis_echeance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avisEcheance = new AvisEcheance();
        $form = $this->createForm(AvisEcheanceType::class, $avisEcheance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avisEcheance);
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_echeance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis_echeance/new.html.twig', [
            'avis_echeance' => $avisEcheance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_echeance_show', methods: ['GET'])]
    public function show(AvisEcheance $avisEcheance): Response
    {
        return $this->render('avis_echeance/show.html.twig', [
            'avis_echeance' => $avisEcheance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_echeance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AvisEcheance $avisEcheance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisEcheanceType::class, $avisEcheance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($avisEcheance->getPaymentStatus() === PaymentStatus::PARTIEL) {
                $partialPayment = $form->get('partialPaymentAmount')->getData();
                if ($partialPayment > 0) {
                    $payment = new Payment();
                    $payment->setAmount($partialPayment);
                    $payment->setPaymentDate(new \DateTimeImmutable());
                    $payment->setAvisEcheance($avisEcheance);
                    // Ajouter le paiement à l'avis d'échéance
                    $avisEcheance->addPayment($payment);
                    $entityManager->persist($payment);

                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_echeance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis_echeance/edit.html.twig', [
            'avis_echeance' => $avisEcheance,
            'form' => $form,
        ]);
    }

    #[Route('/delete_selected', name: 'app_avis_echeance_delete_selected', methods: ['POST'])]
    public function deleteSelected(Request $request, AvisEcheanceService $avisEcheanceService): Response
    {
        // Récupérer les IDs sélectionnés depuis le formulaire
        $selectedIds = $request->request->all('avis_echeances') ?? [];


        if (!empty($selectedIds) && is_array($selectedIds)) {
            // Appeler le service pour supprimer les avis d’échéance sélectionnés
            $avisEcheanceService->deleteSelectedAvisEcheances($selectedIds);
        }

        return $this->redirectToRoute('app_avis_echeance_index');
    }

    #[Route('/{id}', name: 'app_avis_echeance_delete', methods: ['DELETE'])]
    public function delete(Request $request, AvisEcheance $avisEcheance, EntityManagerInterface $entityManager): Response
    {

        // Récupérer le token CSRF depuis les en-têtes
        $csrfToken = $request->headers->get('X-CSRF-TOKEN');

        if ($this->isCsrfTokenValid('delete' . $avisEcheance->getId(), $csrfToken)) {
            $entityManager->remove($avisEcheance);
            $entityManager->flush();

            // Retourne une réponse HTTP 204 No Content pour indiquer que la suppression s'est bien passée
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

        // Retourne une réponse HTTP 403 Forbidden si le token CSRF est invalide
        return new Response('Invalid CSRF token', Response::HTTP_FORBIDDEN);
    }

    #[Route('/{id}/update-payment-status', name: 'app_avis_echeance_update_payment_status', methods: ['POST'])]
    public function updatePaymentStatus(
        Request                $request,
        AvisEcheance           $avisEcheance,
        EntityManagerInterface $entityManager,
        LoggerInterface        $logger
    ): JsonResponse
    {
        // Récupérer les données envoyées par la requête
        $data = json_decode($request->getContent(), true);
        $logger->info('Contenu de $data: ' . print_r($data, true));
        $newStatusValue = $data['payment_status'] ?? null;
        $logger->info('Valeur de payment_status reçue: ' . $newStatusValue);

        $newStatus = $this->findPaymentStatus($newStatusValue);

        if ($newStatus === null) {
            $logger->warning('Statut de paiement invalide pour payment_status: ' . $newStatusValue);
            return new JsonResponse([
                'error' => 'Statut de paiement invalide'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Mettre à jour le statut
            $avisEcheance->setPaymentStatus($newStatus);
            $entityManager->flush();

            $logger->info('Le statut de paiement a été mis à jour avec succès pour l\'avis d\'échéance ID: ' . $avisEcheance->getId());

            return new JsonResponse([
                'message' => 'Le statut de paiement a été mis à jour.',
                'newStatus' => $newStatus->value
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            $logger->error('Erreur lors de la mise à jour du statut : ' . $e->getMessage());
            return new JsonResponse([
                'error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function findPaymentStatus(?string $statusValue): ?PaymentStatus
    {
        if ($statusValue === null) {
            return null;
        }

        return PaymentStatus::tryFrom($statusValue) ?: null;
    }
}
