<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Action pour gérer la page de connexion
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérification du champ honeypot
        if (empty($honeypot)) {

            // Vérification si l'utilisateur est déjà connecté
            if ($this->getUser()) {
                return $this->redirectToRoute('app_home');
            }

            // Récupération de l'erreur de connexion s'il y en a une
            $error = $authenticationUtils->getLastAuthenticationError();

            // Récupération du dernier nom d'utilisateur saisi par l'utilisateur
            $lastUsername = $authenticationUtils->getLastUsername();

        } else {
            // Redirection en cas de tentative de bot
            return $this->redirectToRoute('app_login');

        }

        // Affichage du formulaire de connexion
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }

    // Action pour gérer la déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        
        // Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');

    }
    
}
