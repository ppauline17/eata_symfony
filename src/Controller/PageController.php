<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_page_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('page/home.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);
    }

    #[Route('/page/article/{id}', name: 'app_page_article_show', methods: ['GET'])]
    public function article(Article $article): Response
    {
        return $this->render('page/article.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/accueil-periscolaire', name: 'app_page_periscolaire')]
    public function periscolaire(): Response
    {
        return $this->render('page/accueil_periscolaire.html.twig');
    }

    #[Route('/accueil-loisirs', name: 'app_page_loisirs')]
    public function loisirs(): Response
    {
        return $this->render('page/accueil_loisirs.html.twig');
    }

    #[Route('/association', name: 'app_page_association')]
    public function association(): Response
    {
        return $this->render('page/association.html.twig');
    }

    #[Route('/enfants', name: 'app_page_children')]
    public function children(): Response
    {
        return $this->render('page/children.html.twig');
    }

    #[Route('/parents', name: 'app_page_parents')]
    public function parents(): Response
    {
        return $this->render('page/parents.html.twig');
    }

    #[Route('/informations', name: 'app_page_informations')]
    public function informations(): Response
    {
        return $this->render('page/informations.html.twig');
    }
}
