<?php

namespace App\Controller;

use App\Entity\Lodger;
use App\Form\LodgerType;
use App\Repository\LodgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lodger')]
class LodgerController extends AbstractController
{
    #[Route('/', name: 'app_lodger_index', methods: ['GET'])]
    public function index(LodgerRepository $lodgerRepository): Response
    {
        return $this->render('lodger/index.html.twig', [
            'lodgers' => $lodgerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lodger_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lodger = new Lodger();
        $form = $this->createForm(LodgerType::class, $lodger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lodger);
            $entityManager->flush();

            return $this->redirectToRoute('app_lodger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lodger/new.html.twig', [
            'lodger' => $lodger,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lodger_show', methods: ['GET'])]
    public function show(Lodger $lodger): Response
    {
        return $this->render('lodger/show.html.twig', [
            'lodger' => $lodger,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lodger_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lodger $lodger, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LodgerType::class, $lodger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lodger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lodger/edit.html.twig', [
            'lodger' => $lodger,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lodger_delete', methods: ['POST'])]
    public function delete(Request $request, Lodger $lodger, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lodger->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lodger);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lodger_index', [], Response::HTTP_SEE_OTHER);
    }
}
