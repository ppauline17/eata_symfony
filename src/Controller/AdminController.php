<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\PriceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }

    #[Route('/accueil', name: 'app_admin_home')]
    public function home(): Response
    {
        return $this->render('admin/home.html.twig', [
        ]);
    }
    
    #[Route('/périscolaire', name: 'app_admin_periscolaire')]
    public function periscolaire(): Response
    {
        return $this->render('admin/periscolaire.html.twig', [
        ]);
    }

    #[Route('/periscolaire/liste', name: 'app_admin_periscolaire_list')]
    public function periscolaireList(PriceRepository $priceRepository, CityRepository $cityRepository): Response
    {
        return $this->render('admin/periscolaireList.html.twig', [
            'prices' => $priceRepository->findAll(),
            'cities' => $cityRepository->findAll(),
        ]);
    }

    #[Route('/mercredi', name: 'app_admin_mercredi')]
    public function mercredi(): Response
    {
        return $this->render('admin/mercredi.html.twig', [
        ]);
    }

    #[Route('/mercredi/liste', name: 'app_admin_mercredi_list')]
    public function mercrediList(PriceRepository $priceRepository, CityRepository $cityRepository): Response
    {
        return $this->render('admin/mercrediList.html.twig', [
            'prices' => $priceRepository->findAll(),
            'cities' => $cityRepository->findAll(),
        ]);
    }

    #[Route('/loisirs', name: 'app_admin_loisirs')]
    public function loisirs(): Response
    {
        return $this->render('admin/loisirs.html.twig', [
        ]);
    }

    #[Route('/loisirs/liste', name: 'app_admin_loisirs_list')]
    public function loisirsList(PriceRepository $priceRepository, CityRepository $cityRepository): Response
    {
        return $this->render('admin/loisirsList.html.twig', [
            'prices' => $priceRepository->findAll(),
            'cities' => $cityRepository->findAll(),
        ]);
    }
    
    #[Route('/association', name: 'app_admin_association')]
    public function association(): Response
    {
        return $this->render('admin/association.html.twig', [
        ]);
    }
    
    #[Route('/enfants', name: 'app_admin_children')]
    public function children(): Response
    {
        return $this->render('admin/children.html.twig', [
        ]);
    }
    
    #[Route('/parents', name: 'app_admin_parents')]
    public function parents(): Response
    {
        return $this->render('admin/parents.html.twig', [
        ]);
    }
    
    #[Route('/informations', name: 'app_admin_informations')]
    public function informations(): Response
    {
        return $this->render('admin/informations.html.twig', [
        ]);
    }
}
