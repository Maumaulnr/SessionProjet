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

        $programmes = $programmeRepository->findBy([]);

        return $this->render('programme/index.html.twig', [
            'programmes' => $programmes,
        ]);
    }

    #[Route('/programme/new', name: 'new_programme')]
    #[Route('/programme/{id}/edit', name: 'edit_programme')]
    public function new_edit(Programme $programme = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$programme) 
        {
            $programme = new Programme();
        }
        
        $form = $this->createForm(ProgrammeType::class, $programme);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère les données du formulaire
            $programme = $form->getData();
            // prepare PDO
            $entityManager->persist($programme);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_programme');

        }

        return $this->render('programme/new.html.twig', [
            'formAddProgramme' => $form,
            'edit' => $programme->getId()
        ]);

    }

    #[Route('/programme/{id}/delete', name: 'delete_programme')]
    public function delete(Programme $programme, EntityManagerInterface $entityManager)
    {
        
        // remove : prépare la requête
        $entityManager->remove($programme);
        // Faire la requête SQL DELETE FROM
        $entityManager->flush();

        return $this->redirectToRoute('app_programme'); 

    }


    #[Route('/programme/{id}', name: 'show_programme')]
    public function show(Programme $programme): Response 
    {

        return $this->render('programme/show.html.twig', [
            'programme' => $programme,
        ]);

    }

}
