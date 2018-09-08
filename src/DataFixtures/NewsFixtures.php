<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Faker\Factory;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR'); // on crée une instance de la class Faker

        // créer 3 catégories fakées
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence()) // faker va gérénérer le titre
                ->setDescription($faker->paragraph()) // et la description
            ;
            $manager->persist($category); // je demande au manager de persister cette category

            // créer entre 4 et 6 articles dans chaque catégory
            // je mets ma boucle qui créeait les articles ici avec mt_rand pour au hasard entre 4 et 6 :
            for ($j = 1; $j <= mt_rand(4,6); $j++) { // attention, on a déjà utilisé la variable i plus haut
                $post = new News();
                $content = '<p>'.join($faker->paragraphs(5), '</p><p>') . '</p>'; // génère 5 paragraphes
                $post->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category)
                ;
                $manager->persist($post);

                // on crée maintenant entre 4 et 10 commentaires pour chaque article :
                for ($k = 1; $k<= mt_rand(4,10); $k++) {
                    $comment = new Comment();
                    $content = '<p>'.join($faker->paragraphs(2), '</p><p>') . '</p>'; // génère 2 paragraphes
                    $now = new \DateTime();
                    $interval = $now->diff($post->getCreatedAt()); // donne la différence entre sous forme d'intervalle
                    $days = $interval->days;
                    $minimum = '-' . $days . ' days'; // si days égale 100, on vient d'écrire - 100 days
                    $comment->setAuthor($faker->name())
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween($minimum))
                        ->setPost($post)
                    ;
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
