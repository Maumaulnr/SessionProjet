<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CoursController extends AbstractController
{

    #[Route('/cours', name: 'app_cours')]
    public function index(CoursRepository $coursRepository): Response
    {

        $cours = $coursRepository->findBy([], ["nomModule" => "ASC"]);

        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
        ]);

    }

    #[Route('/cours/new', name: 'new_cours')]
    #[Route('/cours/{id}/edit', name: 'edit_cours')]
    public function new_edit(Cours $cours = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$cours) 
        {
            $cours = new Cours();
        }
        
        $form = $this->createForm(CoursType::class, $cours);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère les données du formulaire
            $cours = $form->getData();
            // prepare PDO
            $entityManager->persist($cours);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_cours');

        }

        return $this->render('cours/new.html.twig', [
            'formAddCours' => $form,
            'edit' => $cours->getId()
        ]);

    }
    

    #[Route('/cours/{id}/delete', name: 'delete_cours')]
    public function delete(Cours $cours, EntityManagerInterface $entityManager)
    {
        // remove : prépare la requête
        $entityManager->remove($cours);
        // Faire la requête SQL DELETE FROM
        $entityManager->flush();

        return $this->redirectToRoute('app_cours'); 
    }


    #[Route('/cours/{id}', name: 'show_cours')]
    public function show(Cours $cours): Response 
    {

        return $this->render('cours/show.html.twig', [
            'cours' => $cours
        ]);

    }

}
