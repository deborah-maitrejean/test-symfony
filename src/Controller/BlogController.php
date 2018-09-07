<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     * @param NewsRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(NewsRepository $repo)
    {
        $all = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'posts' => $all
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
     * @param News $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(News $post) // News c'est le nom du controller
    {
        //$repo = $this->getDoctrine()->getRepository(News::class);
        //$post = $repo->find($id);
        return $this->render('blog/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create()
    {
        $post = new News(); // nouvel article vide prêt à être rempli
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('image', TextType::class)
            ->getForm()
        ;

        return $this->render('blog/create.html.twig', [
            'postForm' => $form->createView()
        ]);
    }
}
