<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAllPaginated($request->query->getInt('page', 1), 10),
            'crud_article' => true
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, #[Autowire('%photo_dir%')] string $imgDir,): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitle(mb_strtoupper($form['title']->getData()));
            $article->setCreatedAt(new DateTimeImmutable());
            if ($photo = $form['picture']->getData()) {
                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                $photo->move($imgDir, $filename);
                $article->setPicture($filename);
            }
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'crud_article' => true
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'crud_article' => true
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, Article $article, 
        EntityManagerInterface $entityManager, 
        #[Autowire('%photo_dir%')] string $imgDir,
    ): Response
    {
        if($article->getPicture()){
            // on défini la valeur de l'ancienne image
            $article->setOldPicture($article->getPicture());
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitle(mb_strtoupper($form['title']->getData()));
            // si une nouvelle image est renseignée
            if ($picture = $form['picture']->getData()) {
                // on remplace le nom de l'image dans la base de données
                $filename = bin2hex(random_bytes(6)).'.'.$picture->guessExtension();
                $picture->move($imgDir, $filename);
                $article->setPicture($filename);

                // si il y avait déjà une image
                if ($old_picture = $form['old_picture']->getData()){
                // on supprime l'ancienne image du dossier
                    $old_picture_path = $this->getParameter("photo_dir").$old_picture;
                    if(file_exists($old_picture_path)){
                        unlink($old_picture_path);
                    }
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'crud_article' => true
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
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

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
