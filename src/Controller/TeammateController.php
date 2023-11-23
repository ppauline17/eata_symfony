<?php

namespace App\Controller;

use App\Entity\Teammate;
use App\Form\TeammateType;
use App\Repository\CategoryRepository;
use App\Repository\TeammateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipe')]
class TeammateController extends AbstractController
{
    #[Route('/{category_label}', name: 'app_teammate_index', methods: ['GET'])]
    public function index(TeammateRepository $teammateRepository, $category_label): Response
    {
        return $this->render('teammate/index.html.twig', [
            'teammates' => $teammateRepository->findByCategory($category_label),
            'crud_teammates' => true,
            'category_label' => $category_label
        ]);
    }

    #[Route('/new/{category_label}', name: 'app_teammate_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        CategoryRepository $categoryRepository,
        $category_label
    ): Response 
    {
        $teammate = new Teammate();
        $form = $this->createForm(TeammateType::class, $teammate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form['hierarchy']->getData() === null){
                $teammate->setHierarchy(3);
            }
            $teammate
                    ->setLastname(mb_strtoupper($form['lastname']->getData()))
                    ->setFirstname(ucfirst(strtolower($form['firstname']->getData())))
                    ->setJob(mb_strtoupper($form['job']->getData()))
                    ->setCategory($categoryRepository->findOneBy(['label' => $category_label]));
            $entityManager->persist($teammate);
            $entityManager->flush();

            return $this->redirectToRoute('app_teammate_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teammate/new.html.twig', [
            'teammate' => $teammate,
            'form' => $form,
            'category_label' => $category_label
        ]);
    }

    #[Route('/show/{id}', name: 'app_teammate_show', methods: ['GET'])]
    public function show(Teammate $teammate): Response
    {
        return $this->render('teammate/show.html.twig', [
            'teammate' => $teammate
        ]);
    }

    #[Route('/edit/{id}', name: 'app_teammate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teammate $teammate, EntityManagerInterface $entityManager, TeammateRepository $teammateRepository): Response
    {
        $form = $this->createForm(TeammateType::class, $teammate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teammate
                    ->setLastname(mb_strtoupper($form['lastname']->getData()))
                    ->setFirstname(ucfirst(strtolower($form['firstname']->getData())))
                    ->setJob(mb_strtoupper($form['job']->getData()));
            $entityManager->flush();

            $category = $teammate->getCategory();
            $category_label = $category->getLabel();

            return $this->redirectToRoute('app_teammate_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teammate/edit.html.twig', [
            'teammate' => $teammate,
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'app_teammate_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Teammate $teammate, 
        EntityManagerInterface $entityManager
        ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teammate->getId(), $request->request->get('_token'))) {
            $category = $teammate->getCategory();
            $category_label = $category->getLabel();

            $entityManager->remove($teammate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_teammate_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
    }
}
