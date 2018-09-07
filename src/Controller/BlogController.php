<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
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
     * @Route("/blog/{id}/edit", name="blog_edit")
     * @param News|null $post
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function form(News $post = null, Request $request, ObjectManager $manager) // $post à null car paramètre que dans une des deux routes
    {
        if (!$post) { // si pas de post, alors on crée un instance de la class News
            $post = new News();
        }

        $form = $this->createForm(NewsType::class, $post); // nom de la class formulairen, a quelle entity je veux le lier

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$post->getId()) { // si l'article n'a pas d'id, donc n'existe pas
                $post->setCreatedAt(new \DateTime()); // là oui, je donne une date de création
            }

            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $post->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'postForm' => $form->createView(),
            'editMode' => $post->getId() !== null  // on veut changer le contenu du bouton, pour qu'il affiche publier ou modifier
        ]);
    }
}
