<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\DocumentRepository;
use App\Repository\InformationRepository;
use App\Repository\PriceRepository;
use App\Repository\TeammateRepository;
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
            'articles' => $articleRepository->findAllPaginated($request->query->getInt('page', 1), 5)
        ]);
    }

    #[Route('/accueil-periscolaire', name: 'app_page_periscolaire')]
    public function periscolaire(TeammateRepository $teammateRepository, InformationRepository $informationRepository, PriceRepository $priceRepository): Response
    {
        return $this->render('page/accueil_periscolaire.html.twig', [
            'teammates' => $teammateRepository->findBy(['category' => 'periscolaire']),
            'information_description' => $informationRepository->findOneBy(['label' => 'app_page_periscolaire_description']),
            'information_time' => $informationRepository->findOneBy(['label' => 'app_page_periscolaire_time']),
            'prices' => $priceRepository->findAll()
        ]);
    }

    #[Route('/accueil-mercredi', name: 'app_page_mercredi')]
    public function mercredi(TeammateRepository $teammateRepository, InformationRepository $informationRepository, PriceRepository $priceRepository): Response
    {
        return $this->render('page/accueil_mercredi.html.twig', [
            'teammates' => $teammateRepository->findBy(['category' => 'mercredi']),
            'information_description' => $informationRepository->findOneBy(['label' => 'app_page_mercredi_description']),
            'information_time' => $informationRepository->findOneBy(['label' => 'app_page_mercredi_time']),
            'prices' => $priceRepository->findAll()
        ]);
    }

    #[Route('/accueil-loisirs', name: 'app_page_loisirs')]
    public function loisirs(TeammateRepository $teammateRepository, InformationRepository $informationRepository, PriceRepository $priceRepository): Response
    {
        return $this->render('page/accueil_loisirs.html.twig', [
            'teammates' => $teammateRepository->findBy(['category' => 'loisirs']),
            'information_description' => $informationRepository->findOneBy(['label' => 'app_page_loisirs_description']),
            'information_time' => $informationRepository->findOneBy(['label' => 'app_page_loisirs_time']),
            'prices' => $priceRepository->findAll()
        ]);
    }

    #[Route('/association', name: 'app_page_association')]
    public function association(TeammateRepository $teammateRepository, InformationRepository $informationRepository): Response
    {
        return $this->render('page/association.html.twig', [
            'teammates' => $teammateRepository->findBy(['category' => 'association']),
            'description' => $informationRepository->findOneBy(['label' => 'app_page_association_description']),
            "informations" => $informationRepository->findOneBy(['label' => 'app_page_association_informations']),
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
    public function informations(InformationRepository $informationRepository, DocumentRepository $documentRepository): Response
    {
        return $this->render('page/informations.html.twig', [
            "informations" => $informationRepository->findOneBy(['label' => 'app_page_infospratiques_informations']),
            'documents' => $documentRepository->findAll()
        ]);
    }
}
