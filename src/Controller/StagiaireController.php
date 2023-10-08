<?php

namespace App\Controller;

use App\Entity\Session;
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

    # Définit une route avec le nom 'app_stagiaire' pour la méthode 'index'.
    # Lorsque l'URL '/stagiaire' est accédée, cette méthode sera exécutée.
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {

        # On injecte la dépendance de StagiaireRepository pour accéder aux données.
        # On récupère la liste des stagiaires depuis la base de données et on les trie par nom.
        $stagiaires = $stagiaireRepository->findBy([], ['nom' => 'ASC']);

        # On rend un template Twig avec la liste des stagiaires pour l'affichage.
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);

    }

    # Définit deux routes, 'new_stagiaire' et 'edit_stagiaire', pour la méthode 'new_edit'.
    # Ces routes permettent d'ajouter un nouveau stagiaire ou de modifier un stagiaire existant.
    #[Route('/stagiaire/new', name: 'new_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function new_edit(Stagiaire $stagiaire = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$stagiaire) 
        {
            # Si aucun stagiaire n'est fourni en paramètre, on crée un nouvel objet Stagiaire.
            $stagiaire = new Stagiaire();
        }
        
        # On crée un formulaire en utilisant StagiaireType et on le relie à l'objet $stagiaire.
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        
        # On gère la soumission du formulaire en utilisant la requête HTTP actuelle.
        $form->handleRequest($request);

        # Si le formulaire est soumis et valide :
        if ($form->isSubmitted() && $form->isValid()) {

            # on récupère les données du formulaire
            $stagiaire = $form->getData();
            # On persiste (prépare) l'objet stagiaire pour l'ajout ou la modification en base de données.
            $entityManager->persist($stagiaire);
            # On exécute la requête SQL pour enregistrer les modifications en base de données.
            $entityManager->flush();

            # On redirige vers la route 'app_stagiaire' après l'ajout ou la modification.
            return $this->redirectToRoute('app_stagiaire');

        }

        # On rend un template Twig avec le formulaire pour ajouter ou modifier un stagiaire.
        return $this->render('stagiaire/new.html.twig', [
            'formAddStagiaire' => $form,
            'edit' => $stagiaire->getId()
        ]);

    }

    # Définit une route 'delete_stagiaire' pour la méthode 'delete'.
    # Cette route permet de supprimer un stagiaire par son ID.
    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityManager)
    {
        
        # On prépare la requête pour supprimer l'objet stagiaire de la base de données.
        $entityManager->remove($stagiaire);

        # On exécute la requête SQL DELETE FROM pour supprimer le stagiaire de la base de données.
        $entityManager->flush();

        # On redirige vers la route 'app_stagiaire' après la suppression.
        return $this->redirectToRoute('app_stagiaire'); 

    }


    # Cette ligne définit une route 'show_stagiaire' pour la méthode 'show'.
    # Cette route permet d'afficher les détails d'un stagiaire par son ID.
    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function show(Stagiaire $stagiaire): Response 
    {

        # On récupère les sessions associées au stagiaire.
        $sessions = $stagiaire->getSessions();

        # On rend un template Twig avec les détails du stagiaire et ses sessions.
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
            'sessions' => $sessions
        ]);

    }

}
