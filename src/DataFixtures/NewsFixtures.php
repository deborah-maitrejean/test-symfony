<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $news = new News();
            $news->setTitle("Titre de l'article n°$i")
            ->setContent("<p>Contenu de l'article n°$i</p>")
            ->setImage("http://placehold.it/350x150")
            ->setCreatedAt(new \DateTime());

            $manager->persist($news);
        }
        $manager->flush();
    }
}
