<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
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
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['label' => 'actualités']);
        $categoryId = $category->getId();

        return $this->render('page/home.html.twig', [
            'articles' => $articleRepository->findBy(['category' => $categoryId], ['createdAt' => 'DESC'], 3)
        ]);
    }

    #[Route('/page/article/{id}', name: 'app_page_article_show', methods: ['GET'])]
    public function article(Article $article): Response
    {
        return $this->render('page/article.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/page/articles/{category_label}', name: 'app_page_articles')]
    public function articles(ArticleRepository $articleRepository, Request $request, $category_label): Response
    {
        return $this->render('page/articles.html.twig', [
            'articles' => $articleRepository->findAllPaginatedBy($request->query->getInt('page', 1), 5, $category_label),
            'category_label' => $category_label
        ]);
    }

    #[Route('/accueil-periscolaire', name: 'app_page_periscolaire')]
    public function periscolaire(
        TeammateRepository $teammateRepository, 
        InformationRepository $informationRepository, 
        PriceRepository $priceRepository,
        DocumentRepository $documentRepository
    ): Response
    {
        return $this->render('page/accueil_periscolaire.html.twig', [
            'teammates' => $teammateRepository->findByCategory('periscolaire'),
            'information_description' => $informationRepository->findOneBy(['label' => 'app_page_periscolaire_description']),
            'information_time' => $informationRepository->findOneBy(['label' => 'app_page_periscolaire_time']),
            'prices' => $priceRepository->findAll(),
            'documents_list' => $informationRepository->findOneBy(['label' => 'app_page_periscolaire_documents']),
            'documents' => $documentRepository->findByCategoryLabel('périscolaire')
        ]);
    }

    #[Route('/accueil-mercredi', name: 'app_page_mercredi')]
    public function mercredi(
        TeammateRepository $teammateRepository, 
        InformationRepository $informationRepository, 
        PriceRepository $priceRepository, 
        CityRepository $cityRepository,
        DocumentRepository $documentRepository
    ): Response
    {
        return $this->render('page/accueil_mercredi.html.twig', [
            'teammates' => $teammateRepository->findByCategory('mercredi'),
            'information_description' => $informationRepository->findOneBy(['label' => 'app_page_mercredi_description']),
            'information_time' => $informationRepository->findOneBy(['label' => 'app_page_mercredi_time']),
            'prices' => $priceRepository->findAll(),
            'cities' => $cityRepository->findAll(),
            'meal' => $cityRepository->findOneBy(['name' => 'ST MARTIN LA PALLU']),
            'documents_list' => $informationRepository->findOneBy(['label' => 'app_page_mercredi_documents']),
            'documents' => $documentRepository->findByCategoryLabel('mercredi')
        ]);
    }

    #[Route('/accueil-loisirs', name: 'app_page_loisirs')]
    public function loisirs(
        TeammateRepository $teammateRepository, 
        InformationRepository $informationRepository, 
        PriceRepository $priceRepository, 
        CityRepository $cityRepository,
        DocumentRepository $documentRepository
    ): Response
    {
        return $this->render('page/accueil_loisirs.html.twig', [
            'teammates' => $teammateRepository->findByCategory('loisirs'),
            'information_description' => $informationRepository->findOneBy(['label' => 'app_page_loisirs_description']),
            'information_time' => $informationRepository->findOneBy(['label' => 'app_page_loisirs_time']),
            'prices' => $priceRepository->findAll(),
            'cities' => $cityRepository->findAll(),
            'documents_list' => $informationRepository->findOneBy(['label' => 'app_page_loisirs_documents']),
            'documents' => $documentRepository->findByCategoryLabel('loisirs')
        ]);
    }

    #[Route('/association', name: 'app_page_association')]
    public function association(
        TeammateRepository $teammateRepository, 
        InformationRepository $informationRepository,
        DocumentRepository $documentRepository
        ): Response
    {
        return $this->render('page/association.html.twig', [
            'teammates' => $teammateRepository->findByCategory('association'),
            'description' => $informationRepository->findOneBy(['label' => 'app_page_association_description']),
            "informations" => $informationRepository->findOneBy(['label' => 'app_page_association_informations']),
            'documents' => $documentRepository->findByCategoryLabel('association')
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
        $formEata = $this->createForm(ContactType::class);
        $formAsso = $this->createForm(ContactType::class);

        if ($formEata->isSubmitted() && $formEata->isValid()) {


            return $this->redirectToRoute('app_page_informations', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('page/informations.html.twig', [
            "informations" => $informationRepository->findOneBy(['label' => 'app_page_infospratiques_informations']),
            'documents' => $documentRepository->findAllWithoutAssociation(),
            'formEata' => $formEata,
            'formAsso' => $formAsso,
        ]);
    }

    #[Route('/mentions', name: 'app_page_mentions')]
    public function mentions(): Response
    {
        return $this->render('page/mentions.html.twig');
    }
}
