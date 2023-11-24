<?php

namespace App\Controller;

use App\Entity\Teammate;
use App\Form\TeammateType;
use App\Repository\CategoryRepository;
use App\Repository\TeammateRepository;
use App\Service\FileUploaderService;
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
        FileUploaderService $fileUploaderService,
        CategoryRepository $categoryRepository,
        $category_label
    ): Response 
    {
        $teammate = new Teammate();
        $form = $this->createForm(TeammateType::class, $teammate, ['category_label' => $category_label]);
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

            // upload du media
            if ($picture = $form['picture']->getData()) {
                $filename = $fileUploaderService->uploadPicture($picture);
                $teammate->setPicture($filename);

                $entityManager->persist($teammate);
                $entityManager->flush();

                return $this->redirectToRoute('app_cropperjs', ['filename' => $filename], Response::HTTP_SEE_OTHER);
            }else{
                $entityManager->persist($teammate);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_teammate_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
            }
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
        $category = $teammate->getCategory();
        $category_label = $category->getLabel();

        return $this->render('teammate/show.html.twig', [
            'teammate' => $teammate,
            'category_label' => $category_label,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_teammate_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Teammate $teammate, 
        EntityManagerInterface $entityManager, 
        FileUploaderService $fileUploaderService
    ): Response
    {
        $category = $teammate->getCategory();
        $category_label = $category->getLabel();

        if (!$teammate->getOldPicture() && $teammate->getPicture()) {
            $teammate->setOldPicture($teammate->getPicture());
        }

        $form = $this->createForm(TeammateType::class, $teammate, ['category_label' => $category_label]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teammate
                    ->setLastname(mb_strtoupper($form['lastname']->getData()))
                    ->setFirstname(ucfirst(strtolower($form['firstname']->getData())))
                    ->setJob(mb_strtoupper($form['job']->getData()));

            // si une nouvelle image est renseignée
            if ($picture = $form['picture']->getData()) {
                $filename = $fileUploaderService->uploadPicture($picture);
                $teammate->setPicture($filename);

                // si il y avait déjà une image ou un document
                if ($old_picture = $form['old_picture']->getData()){
                    // on supprime l'ancienne image du dossier
                    $old_picture_path = $this->getParameter("photo_dir").$old_picture;
                    $old_cropped_picture_path = $this->getParameter("cropped_photo_dir").$old_picture;
                    if(file_exists($old_picture_path)){
                        unlink($old_picture_path);
                    }
                    if(file_exists($old_cropped_picture_path)){
                        unlink($old_cropped_picture_path);
                    }
                }

                $entityManager->persist($teammate);
                $entityManager->flush();

                return $this->redirectToRoute('app_cropperjs', ['filename' => $filename], Response::HTTP_SEE_OTHER);
            }else{
                $teammate->setPicture($teammate->getOldPicture());

                $entityManager->persist($teammate);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_teammate_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
            }
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

            if($picture = $teammate->getPicture()){
                $picture_path = $this->getParameter("photo_dir").$picture;
                $cropped_picture_path = $this->getParameter("cropped_photo_dir").$picture;
                // on la supprime du dossier
                if(file_exists($picture_path)){
                    unlink($picture_path);
                }
                if(file_exists($cropped_picture_path)){
                    unlink($cropped_picture_path);
                }
            }

            $entityManager->remove($teammate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_teammate_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
    }
}
