<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\CategoryRepository;
use App\Repository\DocumentRepository;
use App\Service\CategoryService;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/document')]
class DocumentController extends AbstractController
{
    #[Route('/{category_label}/liste', name: 'app_document_index', methods: ['GET'])]
    public function index(
        DocumentRepository $documentRepository, 
        $category_label,
        CategoryService $categoryService,
    ): Response
    {
        if (!$categoryService->categoryDocumentExists($category_label)) {
            throw $this->createNotFoundException('Page introuvable');
        }
        if($category_label == "infos"){
            return $this->render('document/index.html.twig', [
                'documents' => $documentRepository->findAllWithoutAssociation(),
                'category_label' => $category_label,
            ]);
        }else{
            return $this->render('document/index.html.twig', [
                'documents' => $documentRepository->findByCategoryLabel($category_label),
                'category_label' => $category_label,
            ]);
        }
    }

    #[Route('/{category_label}/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        FileUploaderService $fileUploaderService,
        $category_label,
        CategoryService $categoryService,
    ): Response
    {
        if (!$categoryService->categoryDocumentExists($category_label)) {
            throw $this->createNotFoundException('Page introuvable');
        }
        $chooseCategory = $category_label == "association" ? false : true;
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document, ['is_creation' => true, 'chooseCategory' => $chooseCategory]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($documentSource = $form['documentSource']->getData()) {
                $label = $form['label']->getData();
                $filename = $fileUploaderService->uploadDocument($documentSource, $label);
                $document->setDocumentSource($filename);
            }
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
            'category_label' => $category_label
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{category_label}/edit/{id}', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Document $document, 
        EntityManagerInterface $entityManager, 
        FileUploaderService $fileUploaderService,
        $category_label,
        CategoryService $categoryService,
    ): Response
    {
        if (!$categoryService->categoryDocumentExists($category_label)) {
            throw $this->createNotFoundException('Page introuvable');
        }
        if (!$document->getOldDocument() && $document->getDocumentSource()) {
            $document->setOldDocument($document->getDocumentSource());
        }

        $chooseCategory = $category_label == "association" ? false : true;

        $form = $this->createForm(DocumentType::class, $document, ['chooseCategory' => $chooseCategory]);
        $form->handleRequest($request);


        
        if ($form->isSubmitted() && $form->isValid()) {

            // si un nouveau document est chargé
            if ($documentSource = $form['documentSource']->getData()) {
                $label = $form['label']->getData();
                $filename = $fileUploaderService->uploadDocument($documentSource, $label);
                $document->setDocumentSource($filename);
                if ($old_document = $form['old_document']->getData()){
                    $docPath = $this->getParameter("document_dir").$old_document;
                    // on supprime du dossier
                    if(file_exists($docPath)){
                        unlink($docPath);
                    }
                }
            }else{
                $document->setDocumentSource($document->getOldDocument());
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
            'category_label' => $category_label,
        ]);
    }

    #[Route('/{category_label}/delete/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Document $document, 
        EntityManagerInterface $entityManager,
        $category_label,
        CategoryService $categoryService,
    ): Response
    {
        if (!$categoryService->categoryDocumentExists($category_label)) {
            throw $this->createNotFoundException('Page introuvable');
        }
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $docPath = $this->getParameter("document_dir").$document->getDocumentSource();
            // on supprime du dossier
            if(file_exists($docPath)){
                unlink($docPath);
            }
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
    }
}
