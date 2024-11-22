<?php

namespace App\Controller;

use App\Entity\AvisEcheance;
use App\Form\AvisEcheanceType;
use App\Repository\AvisEcheanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('avis_echeance/index.html.twig', [
            'avis_echeances' => $avisEcheanceRepository->findAll(),
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
}
