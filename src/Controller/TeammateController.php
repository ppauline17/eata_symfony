<?php

namespace App\Controller;

use App\Entity\Teammate;
use App\Form\TeammateType;
use App\Repository\TeammateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipe')]
class TeammateController extends AbstractController
{
    #[Route('/{category}', name: 'app_teammate_index', methods: ['GET'])]
    public function index(TeammateRepository $teammateRepository, $category): Response
    {
        return $this->render('teammate/index.html.twig', [
            'teammates' => $teammateRepository->findBy(['category' => $category]),
            'crud_teammates' => true,
            'category' => $category
        ]);
    }

    #[Route('/new/{category}', name: 'app_teammate_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        $category
    ): Response 
    {
        $teammate = new Teammate();
        $form = $this->createForm(TeammateType::class, $teammate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teammate->setCategory($category);
            $entityManager->persist($teammate);
            $entityManager->flush();

            return $this->redirectToRoute('app_teammate_index', ['category' => $category], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teammate/new.html.twig', [
            'teammate' => $teammate,
            'form' => $form,
            'crud_teammates' => true
        ]);
    }

    #[Route('/show/{id}', name: 'app_teammate_show', methods: ['GET'])]
    public function show(Teammate $teammate): Response
    {
        return $this->render('teammate/show.html.twig', [
            'teammate' => $teammate,
            'crud_teammates' => true
        ]);
    }

    #[Route('/edit/{id}', name: 'app_teammate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teammate $teammate, EntityManagerInterface $entityManager, TeammateRepository $teammateRepository): Response
    {
        $form = $this->createForm(TeammateType::class, $teammate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_teammate_index', [
                'teammates' => $teammateRepository->findBy(['category' => $teammate->getCategory()]),
                'crud_teammates' => true,
                'category' => $teammate->getCategory()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teammate/edit.html.twig', [
            'teammate' => $teammate,
            'form' => $form,
            'crud_teammates' => true
        ]);
    }

    #[Route('/delete/{id}', name: 'app_teammate_delete', methods: ['POST'])]
    public function delete(Request $request, Teammate $teammate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teammate->getId(), $request->request->get('_token'))) {
            $category = $teammate->getCategory();
            $entityManager->remove($teammate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_teammate_index', ['category' => $category], Response::HTTP_SEE_OTHER);
    }
}
