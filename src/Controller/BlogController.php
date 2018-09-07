<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $post = new News();
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('image', TextType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { // si le form est envoyé et valide
            $post->setCreatedAt(new \DateTime());

            $manager->persist($post); // faire persiter les données
            $manager->flush(); // balancer la requête

            return $this->redirectToRoute('blog_show', ['id' => $post->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'postForm' => $form->createView()
        ]);
    }
}
