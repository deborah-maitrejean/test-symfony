<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(News::class); // on crée une variable $repo qui demande à Doctrine le repository qui gère l'entité News
        $post = $repo->find(12); // trouve l'article 12
        $post = $repo->findOneByTitle('Titre de l\'article'); // trouve l'article dont le titre est
        $posts = $repo->findByTitle('Titre de l\'article'); // trouve tous les articles dont le titre est
        $all = $repo->findAll(); // tous les articles

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            // je passe à Twig l'ensemble des articles:
            'posts' => $all // je crée une variable posts qui contient tous les articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/article/{id}", name="blog_show")
     */
    public function show($id) // je passe l'id à la function
    {
        $repo = $this->getDoctrine()->getRepository(News::class); // je crée un repository
        $post = $repo->find($id); // trouve l'article qui à l'id envoyé en paramètre
        return $this->render('blog/show.html.twig', [
            'post' => $post
        ]); // je passe un tableau avec les variables que twig va utiliser
    }
}
