<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\TeammateRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_page_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('page/home.html.twig', [
            'articles' => $articleRepository->findBy([], ['createdAt' => 'DESC'], 3)
        ]);
    }

    #[Route('/page/article/{id}', name: 'app_page_article_show', methods: ['GET'])]
    public function article(Article $article): Response
    {
        return $this->render('page/article.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/page/articles', name: 'app_page_articles')]
    public function articles(ArticleRepository $articleRepository, Request $request): Response
    {
        return $this->render('page/articles.html.twig', [
            'articles' => $articleRepository->findAllPaginated($request->query->getInt('page', 1))
        ]);
    }

    #[Route('/accueil-periscolaire', name: 'app_page_periscolaire')]
    public function periscolaire(TeammateRepository $teammateRepository): Response
    {
        return $this->render('page/accueil_periscolaire.html.twig', [
            'teammates' => $teammateRepository->findBy(['category' => 'periscolaire'])
        ]);
    }

    #[Route('/accueil-loisirs', name: 'app_page_loisirs')]
    public function loisirs(TeammateRepository $teammateRepository): Response
    {
        return $this->render('page/accueil_loisirs.html.twig', [
            'teammates' => $teammateRepository->findBy(['category' => 'loisirs'])
        ]);
    }

    #[Route('/association', name: 'app_page_association')]
    public function association(TeammateRepository $teammateRepository): Response
    {
        return $this->render('page/association.html.twig', [
            'teammates' => $teammateRepository->findBy(['category' => 'association'])
        ]);
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
