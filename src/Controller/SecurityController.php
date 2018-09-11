<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) // en paramètres pour demander avec injection
    {
        $user = new User(); // a quel objet on relie les champs du form
        $form = $this->createForm(RegistrationType::class, $user); // on instancie le formulaire

        $form->handleRequest($request); // analyse la requête
        if ($form->isSubmitted() && $form->isValid()) {
            // avant de persister, on encode le mot de passe
            $hash = $encoder->encodePassword($user, $user->getPassword());
            // et on le set
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [ // on rend, on retourne ce fichier
            'form' => $form->createView() // avec des variables qu'il puisse utiliser
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){}
}
