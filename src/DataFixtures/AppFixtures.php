<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Factory\ArticleFactory;
use App\Factory\TeammateFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ArticleFactory::createMany(10);
        UserFactory::createOne();
        $this->loadTeammatesPeriscolaire();
        $this->loadTeammatesLoisirs();
        $this->loadTeammatesAssociation();

        $manager->flush();
    }

    public function loadTeammatesPeriscolaire()
    {
        TeammateFactory::createOne([
            'lastname' => "DAUNAY",
            'firstname' => "Virginie",
            'category' => 'periscolaire',
            'job' => 'directrice eata',
            'hierarchy' => 1
        ]);

        TeammateFactory::createOne([
            'lastname' => "MASSONNEAU",
            'firstname' => "Stéphanie",
            'category' => 'periscolaire',
            'job' => 'animatrice/économe',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "MICHAUD",
            'firstname' => "Lucie",
            'category' => 'periscolaire',
            'job' => 'animatrice/référente',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "DURAND",
            'firstname' => "Dylan",
            'category' => 'periscolaire',
            'job' => 'animateur',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "JAMET",
            'firstname' => "Charlyne",
            'category' => 'periscolaire',
            'job' => 'animatrice',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "PAILLOUX",
            'firstname' => "Juliette",
            'category' => 'periscolaire',
            'job' => 'animatrice',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "FAUCHER",
            'firstname' => "Anne-Judith",
            'category' => 'periscolaire',
            'job' => 'animatrice',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "FAUCHER",
            'firstname' => "Gabrielle",
            'category' => 'periscolaire',
            'job' => 'animatrice',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "GUERIN",
            'firstname' => "Nathalie",
            'category' => 'periscolaire',
            'job' => 'animatrice',
            'hierarchy' => 3
        ]);
    }

    public function loadTeammatesLoisirs()
    {
        TeammateFactory::createOne([
            'lastname' => "DAUNAY",
            'firstname' => "Virginie",
            'category' => 'loisirs',
            'job' => 'directrice eata',
            'hierarchy' => 1
        ]);

        TeammateFactory::createOne([
            'lastname' => "MICHAUD",
            'firstname' => "Lucie",
            'category' => 'loisirs',
            'job' => 'directrice adjointe',
            'hierarchy' => 2
        ]);

        TeammateFactory::createOne([
            'lastname' => "DURAND",
            'firstname' => "Dylan",
            'category' => 'loisirs',
            'job' => 'animateur',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "JAMET",
            'firstname' => "Charlyne",
            'category' => 'loisirs',
            'job' => 'animatrice',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "PAILLOUX",
            'firstname' => "Juliette",
            'category' => 'loisirs',
            'job' => 'animatrice',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "FAUCHER",
            'firstname' => "Gabrielle",
            'category' => 'loisirs',
            'job' => 'animatrice',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "FAUCHER",
            'firstname' => "Anne-Judith",
            'category' => 'loisirs',
            'job' => 'agent de service',
            'hierarchy' => 3
        ]);
        TeammateFactory::createOne([
            'lastname' => "FAUCHER",
            'firstname' => "Salome",
            'category' => 'loisirs',
            'job' => 'agent de service',
            'hierarchy' => 3
        ]);
    }

    public function loadTeammatesAssociation()
    {
        TeammateFactory::createOne([
            'lastname' => "SONNARD",
            'firstname' => "Emmanuel",
            'category' => 'association',
            'job' => 'président',
            'hierarchy' => 1
        ]);

        TeammateFactory::createOne([
            'lastname' => "TERRASSIN",
            'firstname' => "Sandra",
            'category' => 'association',
            'job' => 'présidente adjointe',
            'hierarchy' => 2
        ]);

        TeammateFactory::createOne([
            'lastname' => "NEYMOND",
            'firstname' => "Julien",
            'category' => 'association',
            'job' => 'trésorier',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "CHEVOLEAU",
            'firstname' => "Sylvie",
            'category' => 'association',
            'job' => 'trésorière adjointe',
            'hierarchy' => 3
        ]);

        TeammateFactory::createOne([
            'lastname' => "MAGNAIN",
            'firstname' => "Mathias",
            'category' => 'association',
            'job' => 'secrétaire',
            'hierarchy' => 3
        ]);

    }

}
