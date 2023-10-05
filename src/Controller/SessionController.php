<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{

    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {

        $sessions = $sessionRepository->findBy([], ['nomSession' => 'ASC']);

        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    

    #[Route('/{formation_id}/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]    
    public function add(EntityManagerInterface $entityManager, Session $session = null, Request $request, $formation_id, FormationRepository $formationRepository): Response
    {

        if (!$session) {
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session); // Crée le formulaire
        $form->handleRequest($request); // Récupère les données du formulaire

        $formation = $formationRepository->find($formation_id);
              
        if ($form->isSubmitted() && $form->isValid()) { // Vérifie que le formulaire a été soumis et qu'il est valide

            $session = $form->getData(); //Hydrate l'objet $session avec les données du formulaire

            $entityManager->persist($session); // Prépare l'insertion en base de données

            $entityManager->flush(); // Exécute l'insertion en base de données

            return $this->redirectToRoute('app_session'); // Redirige vers la liste des sessions

        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('session/new.html.twig', [
            'form' => $form->createView(), // Envoie le formulaire à la vue
            'edit' => $session->getId(),
			'sessionId' => $session->getId()
        ]);
        
    }



    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager)
    {
        
        // remove : prépare la requête
        $entityManager->remove($session);
        // Faire la requête SQL DELETE FROM
        $entityManager->flush();

        return $this->redirectToRoute('app_session'); 

    }


    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository): Response 
    {

        $stagiaires = $session->getStagiaires();
        $programmes = $session->getProgrammes();
        
        $stagiaireNotInSession = $sessionRepository->findByStagiairesNotInSession($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'programmes' => $programmes,
            'stagiaireNotInSession' => $stagiaireNotInSession,
        ]);

    }

}
