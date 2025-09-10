<?php

namespace App\Controller;

use App\Entity\AvisEcheance;
use App\Enum\PaymentStatus;
use App\Form\AvisEcheanceType;
use App\Repository\AvisEcheanceRepository;
use App\Service\AvisEcheanceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/avis/echeance')]
class AvisEcheanceController extends AbstractController
{
    #[Route('/', name: 'app_avis_echeance_index', methods: ['GET'])]
    public function index(AvisEcheanceRepository $avisEcheanceRepository): Response
    {
        return $this->render('avis_echeance/index.html.twig', [
            'avis_echeances' => $avisEcheanceRepository->findAllSortedByDateAndLodgerName(),
            'payment_statuses' => PaymentStatus::cases(),
        ]);
    }

    #[Route('/generate', name: 'app_avis_echeance_generate', methods: ['POST'])]
    public function generateAvisEcheances(AvisEcheanceService $avisEcheanceService): Response
    {
        $avisEcheanceService->generateAllAvisEcheances();
        $this->addFlash('success', 'Tous les avis d’échéance ont été générés.');

        return $this->redirectToRoute('app_avis_echeance_index');
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

            return $this->redirectToRoute('app_avis_echeance_index');
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
    public function edit(Request $request, AvisEcheance $avisEcheance, AvisEcheanceService $avisEcheanceService): Response
    {
        $form = $this->createForm(AvisEcheanceType::class, $avisEcheance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisEcheanceService->handlePartialPayment($avisEcheance, $form);
            return $this->redirectToRoute('app_avis_echeance_index');
        }

        return $this->render('avis_echeance/edit.html.twig', [
            'avis_echeance' => $avisEcheance,
            'form' => $form,
        ]);
    }

    #[Route('/delete_selected', name: 'app_avis_echeance_delete_selected', methods: ['POST'])]
    public function deleteSelected(Request $request, AvisEcheanceService $avisEcheanceService): Response
    {
        $selectedIds = $request->request->all('avis_echeances') ?? [];

        if (!empty($selectedIds) && is_array($selectedIds)) {
            $avisEcheanceService->deleteSelectedAvisEcheances($selectedIds);
            $this->addFlash('success', "Les avis sélectionnés ont été supprimés.");
        }

        return $this->redirectToRoute('app_avis_echeance_index');
    }

    #[Route('/{id}', name: 'app_avis_echeance_delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, AvisEcheance $avisEcheance, AvisEcheanceService $avisEcheanceService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $avisEcheance->getId(), $request->request->get('_token'))) {
            $avisEcheanceService->deleteAvisEcheance($avisEcheance);
            $this->addFlash('success', "L'avis d'échéance a été supprimé avec succès.");
        }

        return $this->redirectToRoute('app_avis_echeance_index');
    }

    #[Route('/{id}/update-payment-status', name: 'app_avis_echeance_update_payment_status', methods: ['POST'])]
    public function updatePaymentStatus(Request $request, AvisEcheance $avisEcheance, AvisEcheanceService $avisEcheanceService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newStatusValue = $data['payment_status'] ?? null;

        $newStatus = PaymentStatus::tryFrom($newStatusValue);

        if (!$newStatus) {
            return new JsonResponse(['error' => 'Statut de paiement invalide'], Response::HTTP_BAD_REQUEST);
        }

        $avisEcheanceService->changePaymentStatus($avisEcheance, $newStatus);

        return new JsonResponse([
            'message' => 'Le statut de paiement a été mis à jour.',
            'newStatus' => $newStatus->value
        ], Response::HTTP_OK);
    }
}
