<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Form\ProgrammeType;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme', name: 'app_programme')]
    public function index(ProgrammeRepository $programmeRepository): Response
    {

        # Récupération de tous les programmes depuis le ProgrammeRepository
        $programmes = $programmeRepository->findBy([]);

        # Rendu d'un modèle Twig avec les programmes récupérés pour affichage
        return $this->render('programme/index.html.twig', [
            'programmes' => $programmes,
        ]);
    }

    # Indique que cette fonction est associée à la route '/programme/{id}' et qu'elle est nommée 'show_programme'.
    # Lorsque cette route est appelée, cette fonction sera exécutée.
    #[Route('/programme/{id}', name: 'show_programme')]
    # Ceci est la signature de la fonction "show". Elle prend un paramètre "$programme" de type "Programme".
    # Le paramètre "$programme" est injecté automatiquement par Symfony en fonction de la valeur de "{id}" dans l'URL.  
    public function show(Programme $programme): Response 
    {

        # Renvoie une réponse HTTP à l'utilisateur.
        # En utilisant "render", on génère la vue Twig associée au template 'programme/show.html.twig'.
        # On passe également l'objet "$programme" à la vue pour qu'elle puisse afficher les détails du programme.
        return $this->render('programme/show.html.twig', [
            'programme' => $programme,
        ]);

    }

}
