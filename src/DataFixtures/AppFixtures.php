<?php

namespace App\DataFixtures;

use App\Factory\ArticleFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ArticleFactory::createMany(10);

        $manager->flush();
    }

}
