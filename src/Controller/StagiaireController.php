<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {

        $stagiaires = $stagiaireRepository->findBy([], ['nom' => 'ASC']);

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);

    }

    #[Route('/stagiaire/new', name: 'new_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function new_edit(Stagiaire $stagiaire = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$stagiaire) 
        {
            $stagiaire = new Stagiaire();
        }
        
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère les données du formulaire
            $stagiaire = $form->getData();
            // prepare PDO
            $entityManager->persist($stagiaire);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire');

        }

        return $this->render('stagiaire/new.html.twig', [
            'formAddStagiaire' => $form,
            'edit' => $stagiaire->getId()
        ]);

    }


    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityManager)
    {
        
        // remove : prépare la requête
        $entityManager->remove($stagiaire);
        // Faire la requête SQL DELETE FROM
        $entityManager->flush();

        return $this->redirectToRoute('app_stagiaire'); 

    }



    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function show(Stagiaire $stagiaire): Response 
    {

        $sessions = $stagiaire->getSessions();

        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
            'sessions' => $sessions
        ]);

    }

}
