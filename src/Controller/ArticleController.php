<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\FileUploaderService;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/{category_label}/liste', name: 'app_article_index', methods: ['GET'])]
    public function index(
        ArticleRepository $articleRepository, 
        Request $request, 
        $category_label,
        CategoryService $categoryService,
    ): Response
    {
        if (!$categoryService->categoryArticleExists($category_label)) {
            throw $this->createNotFoundException('Page introuvable');
        }
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAllPaginatedBy($request->query->getInt('page', 1), 10, $category_label),
            'crud_article' => true,
            'category_label' => $category_label
        ]);
    }

    #[Route('/{category_label}/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        Security $security,
        FileUploaderService $fileUploaderService,
        $category_label,
        CategoryRepository $categoryRepository,
        CategoryService $categoryService,
        ): Response
    {
        if (!$categoryService->categoryArticleExists($category_label)) {
            throw $this->createNotFoundException('Page introuvable');
        }
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitle(mb_strtoupper($form['title']->getData()))
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setUser($security->getUser())
                    ->setCategory($categoryRepository->findOneBy(['label' => $category_label]));

            // upload du media
            if ($picture = $form['picture']->getData()) {
                $filename = $fileUploaderService->uploadPicture($picture);
                $article->setPicture($filename);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'crud_article' => true,
            'category_label' => $category_label
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, Article $article, 
        EntityManagerInterface $entityManager, 
        FileUploaderService $fileUploaderService
        ): Response
    {
        if (!$article->getOldPicture() && $article->getPicture()) {
            $article->setOldPicture($article->getPicture());
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitle(mb_strtoupper($form['title']->getData()));

            // si une nouvelle image est renseignée
            if ($picture = $form['picture']->getData()) {
                // on remplace le nom de l'image dans la base de données
                $filename = $fileUploaderService->uploadPicture($picture);
                $article->setPicture($filename);
                // si il y avait déjà une image ou un document
                if ($old_picture = $form['old_picture']->getData()){
                    // on supprime l'ancienne image du dossier
                    $old_picture_path = $this->getParameter("photo_dir").$old_picture;
                    if(file_exists($old_picture_path)){
                        unlink($old_picture_path);
                    }
                }
            }else{
                $article->setPicture($article->getOldPicture());
            }
            
            $entityManager->flush();

            $category = $article->getCategory();
            $category_label = $category->getLabel();

            return $this->redirectToRoute('app_article_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Article $article, 
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $category = $categoryRepository->findOneBy(['id' => $article->getCategory()]);
            $category_label = $category->getLabel();
            // si on a une image
            if($picture = $article->getPicture()){
                $picture_path = $this->getParameter("photo_dir").$picture;
                // on la supprime du dossier
                if(file_exists($picture_path)){
                    unlink($picture_path);
                }
            }
            
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', ['category_label' => $category_label], Response::HTTP_SEE_OTHER);
    }
}
