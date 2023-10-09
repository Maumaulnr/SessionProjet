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

    # Indique que cette fonction est associée à la route '/cours' et qu'elle est nommée 'app_cours'.
    # Lorsque cette route est appelée, cette fonction sera exécutée.
    #[Route('/cours', name: 'app_cours')]
    # Ceci est la signature de la fonction "index". Elle prend un paramètre "$coursRepository" de type "CoursRepository".
    # Le paramètre "$coursRepository" est injecté automatiquement par Symfony grâce à la dépendance définie dans la route.
    public function index(CoursRepository $coursRepository): Response
    {
        # Récupère tous les cours en utilisant "$coursRepository->findBy()".
        # Les cours sont triés par ordre alphabétique croissant du champ "nomModule" grâce à la clause ["nomModule" => "ASC"].
        $cours = $coursRepository->findBy([], ["nomModule" => "ASC"]);

        # Renvoie une réponse HTTP à l'utilisateur.
        # En utilisant "render", on génère la vue Twig associée au template 'cours/index.html.twig'.
        # On passe également la liste des cours "$cours" à la vue pour qu'elle puisse les afficher.
        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
        ]);

    }

    # Cette méthode gère à la fois la création et la modification d'un cours.
    # Elle est associée à deux routes différentes : 'new_cours' pour la création et 'edit_cours' pour la modification.
    #[Route('/admin/cours/new', name: 'new_cours')]
    #[Route('/admin/cours/{id}/edit', name: 'edit_cours')]
    public function new_edit(Cours $cours = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$cours) 
        {
            # Si l'objet $cours est null, cela signifie que l'utilisateur souhaite créer un nouveau cours.
            $cours = new Cours();
        }
        
        # Création d'un formulaire en utilisant la classe CoursType et l'objet $cours.
        $form = $this->createForm(CoursType::class, $cours);
        
        # Gestion de la soumission du formulaire et de sa validation.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # on récupère les données du formulaire
            $cours = $form->getData();

            # prepare PDO
            $entityManager->persist($cours);

            # execute PDO
            $entityManager->flush();

            # Redirection vers la page d'affichage de la liste des cours après la création ou la modification.
            return $this->redirectToRoute('app_cours');

        }

        # Affichage du formulaire de création ou de modification du cours.
        return $this->render('cours/new.html.twig', [
            'formAddCours' => $form,
            'edit' => $cours->getId()
        ]);

    }
    
    # Cette méthode est associée à la route '/cours/{id}/delete' et est nommée 'delete_cours'.
    #[Route('/admin/cours/{id}/delete', name: 'delete_cours')]
    public function delete(Cours $cours, EntityManagerInterface $entityManager)
    {

        # Préparation de la requête pour supprimer l'entité $cours.
        $entityManager->remove($cours);

        # Exécution de la requête SQL DELETE FROM pour supprimer l'entité de la base de données.
        $entityManager->flush();

        # Redirection vers la page d'affichage de la liste des cours après la suppression.
        return $this->redirectToRoute('app_cours');

    }

    # Cette méthode est associée à la route '/cours/{id}' et est nommée 'show_cours'.
    #[Route('/cours/{id}', name: 'show_cours')]
    public function show(Cours $cours): Response 
    {
        
         # Affichage de la page de détails d'un cours en utilisant la vue Twig associée au template 'cours/show.html.twig'.
        # L'objet $cours est passé à la vue pour l'affichage des détails du cours.
        return $this->render('cours/show.html.twig', [
            'cours' => $cours
        ]);

    }

}
