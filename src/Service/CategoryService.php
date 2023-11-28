<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function categoryArticleExists($category_label)
    {
        $articleCategory = [
            'actualités',
            'activités',
            'recettes',
            'dessins',
            'projets',
            'bons plans',
            'conseils',
            'évènements',
            'album photos',
        ];
        return in_array($category_label, $articleCategory);
    }

    public function categoryTeammateExists($category_label)
    {
        $articleCategory = [
            'périscolaire',
            'mercredi',
            'loisirs',
            'association',
        ];
        return in_array($category_label, $articleCategory);
    }

    public function categoryDocumentExists($category_label)
    {
        $articleCategory = [
            'périscolaire',
            'mercredi',
            'loisirs',
            'association',
            'infos',
        ];
        return in_array($category_label, $articleCategory);
    }
}