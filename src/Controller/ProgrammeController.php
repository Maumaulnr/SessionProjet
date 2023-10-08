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

        // Récupération de tous les programmes depuis le ProgrammeRepository
        $programmes = $programmeRepository->findBy([]);

        // Rendu d'un modèle Twig avec les programmes récupérés pour affichage
        return $this->render('programme/index.html.twig', [
            'programmes' => $programmes,
        ]);
    }


    #[Route('/programme/{id}', name: 'show_programme')]
    public function show(Programme $programme): Response 
    {

        return $this->render('programme/show.html.twig', [
            'programme' => $programme,
        ]);

    }

}
