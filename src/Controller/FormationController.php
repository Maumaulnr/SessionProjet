<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formation;
use App\Form\SessionType;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{

    # Cette méthode gère l'affichage de la liste des formations.
    # Elle est associée à la route '/formation' et est nommée 'app_formation'.
    #[Route('/formation', name: 'app_formation')]
    public function index(FormationRepository $formationRepository): Response
    {

        # Récupération de la liste des formations depuis le repository, triées par ordre alphabétique du nom.
        $formations = $formationRepository->findBy([], ["nomFormation" => "ASC"]);

        # Affichage de la page de liste des formations en utilisant la vue Twig associée au template 'formation/index.html.twig'.
        return $this->render('formation/index.html.twig', [
            'formations' => $formations
        ]);

    }


    # Cette méthode gère à la fois la création et la modification d'une formation.
    # Elle est associée à deux routes différentes : 'new_formation' pour la création et 'edit_formation' pour la modification.
    #[Route('/formation/new', name: 'new_formation')]
    #[Route('/formation/{id}/edit', name: 'edit_formation')]
    public function new_edit(Formation $formation = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$formation)
        {
            # Si l'objet $formation est null, cela signifie que l'utilisateur souhaite créer une nouvelle formation.
            $formation = new Formation();
        }

        # Création d'un formulaire en utilisant la classe FormationType et l'objet $formation.
        $form = $this->createForm(FormationType::class, $formation);

        # Gestion de la soumission du formulaire et de sa validation.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            # Si le formulaire est soumis et valide, les données du formulaire sont extraites dans $formation.
            $formation = $form->getData();

            # Préparation et exécution de la requête pour enregistrer la formation dans la base de données.
            $entityManager->persist($formation);
            # execute PDO
            $entityManager->flush();

            # Redirection vers la page d'affichage de la liste des formations après la création ou la modification.
            return $this->redirectToRoute('app_formation');

        }

        # Affichage du formulaire de création ou de modification de la formation.
        return $this->render('formation/new.html.twig', [
            'formAddFormation' => $form,
            'edit' => $formation->getId()
        ]);

    }
    
    # Cette méthode est associée à la route '/formation/{id}/delete' et est nommée 'delete_formation'.
    #[Route('/formation/{id}/delete', name: 'delete_formation')]
    public function delete(Formation $formation, EntityManagerInterface $entityManager)
    {
        
        # Préparation de la requête pour supprimer l'entité $formation.
        $entityManager->remove($formation);

        # Exécution de la requête SQL DELETE FROM pour supprimer l'entité de la base de données.
        $entityManager->flush();

        # Redirection vers la page d'affichage de la liste des formations après la suppression.
        return $this->redirectToRoute('app_formation'); 

    }

    # Cette méthode est associée à la route '/formation/{id}' et est nommée 'show_formation'.
    #[Route('/formation/{id}', name: 'show_formation')]
    public function show(Formation $formation): Response 
    {

        # Affichage de la page de détails d'une formation en utilisant la vue Twig associée au template 'formation/show.html.twig'.
        # L'objet $formation est passé à la vue pour l'affichage des détails de la formation.
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);

    }

}
