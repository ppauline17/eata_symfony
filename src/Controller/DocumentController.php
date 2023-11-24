<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/document')]
class DocumentController extends AbstractController
{
    #[Route('/liste/{category_label}', name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository, $category_label): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findBy(['category' => $category_label]),
        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document, ['is_creation' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($documentSource = $form['documentSource']->getData()) {
                $label = $form['label']->getData();
                $filename = $fileUploaderService->uploadDocument($documentSource, $label);
                $document->setDocumentSource($filename);
            }
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService): Response
    {
        if (!$document->getOldDocument() && $document->getDocumentSource()) {
            $document->setOldDocument($document->getDocumentSource());
        }

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form['documentSource']->getData());
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

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $docPath = $this->getParameter("document_dir").$document->getDocumentSource();
            // on supprime du dossier
            if(file_exists($docPath)){
                unlink($docPath);
            }
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
