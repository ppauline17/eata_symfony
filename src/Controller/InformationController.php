<?php

namespace App\Controller;

use App\Entity\Information;
use App\Form\InformationType;
use App\Repository\InformationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/information')]
class InformationController extends AbstractController
{
    #[Route('/{label}/edit', name: 'app_information_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Information $information, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InformationType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_information_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('information/edit.html.twig', [
            'information' => $information,
            'form' => $form,
        ]);
    }
}
