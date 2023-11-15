<?php

namespace App\Controller;

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

    #[Route('/mercredi', name: 'app_admin_mercredi')]
    public function mercredi(): Response
    {
        return $this->render('admin/mercredi.html.twig', [
        ]);
    }

    #[Route('/loisirs', name: 'app_admin_loisirs')]
    public function loisirs(): Response
    {
        return $this->render('admin/loisirs.html.twig', [
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
