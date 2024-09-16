<?php

namespace App\Controller;

use App\Entity\Redactor;
use App\Form\RedactorType;
use App\Repository\RedactorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/redactor')]
final class RedactorController extends AbstractController
{
    #[Route('/', name: 'redactor_index', methods: ['GET', 'POST'])]
    public function index(Request $request, RedactorRepository $redactorRepository): Response
    {
        $redactors = $redactorRepository->findAll();

        $selectedRedactorId = $request->request->get('redactor', null);
        $selectedRedactor = null;

        if ($selectedRedactorId) {
            $selectedRedactor = $redactorRepository->find($selectedRedactorId);
        }

        return $this->render('redactor/redactor.html.twig', [
            'redactors' => $redactors,
            'selectedRedactor' => $selectedRedactor,
        ]);
    }

    #[Route('/new', name: 'redactor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $redactor = new Redactor();
        $form = $this->createForm(RedactorType::class, $redactor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($redactor);
            $entityManager->flush();

            return $this->redirectToRoute('redactor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redactor/new.html.twig', [
            'redactor' => $redactor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'redactor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Redactor $redactor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RedactorType::class, $redactor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('redactor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redactor/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'redactor_delete', methods: ['POST'])]
    public function delete(Request $request, Redactor $redactor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $redactor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($redactor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('redactor_index', [], Response::HTTP_SEE_OTHER);
    }
}
