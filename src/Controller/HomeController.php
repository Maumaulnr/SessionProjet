<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    # Cette méthode gère l'affichage de la page d'accueil de l'application.
    # Elle est associée à la route '/home' et est nommée 'app_home'.
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {

        # Affichage de la page d'accueil en utilisant la vue Twig associée au template 'home/index.html.twig'.
        # Aucune donnée n'est transmise à la vue dans cet exemple, car le tableau vide est utilisé comme contexte.
        return $this->render('home/index.html.twig', []);
        
    }
}
