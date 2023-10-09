<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{

    # Indique que cette fonction est associée à la route '/session' et qu'elle est nommée 'app_session'.
    # Lorsque cette route est appelée, cette fonction sera exécutée.
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {

        # Récupération de toutes les sessions ordonnées par nom
        $sessions = $sessionRepository->findBy([], ['nomSession' => 'ASC']);

        # Renvoie une réponse HTTP à l'utilisateur.
        # En utilisant "render", on génère la vue Twig associée au template 'session/index.html.twig'.
        # On passe également les données des sessions à la vue pour qu'elle puisse les afficher.
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);

    }

    
    # Indique que cette fonction est associée à deux routes : '/{formation_id}/session/new' et '/session/{id}/edit'.
    # Elle est nommée 'new_session' pour la première route et 'edit_session' pour la deuxième route.
    # Lorsque l'une de ces routes est appelée, cette fonction sera exécutée.
    # Créer une session à partir de l'id d'une formation
    # Modifier une session
    #[Route('/admin/{formation_id}/session/new', name: 'new_session')]
    #[Route('/admin/session/{id}/edit', name: 'edit_session')]    
    public function add(EntityManagerInterface $entityManager, Session $session = null, Request $request, $formation_id, FormationRepository $formationRepository): Response
    {

        if (!$session) {
            # Cette condition vérifie si "$session" est nulle. Si c'est le cas, elle crée une nouvelle instance de "Session".
            $session = new Session();
        }

        # Crée un formulaire en utilisant le type de formulaire "SessionType" et l'objet "$session".
        $form = $this->createForm(SessionType::class, $session);

        # Gère la requête HTTP entrante, ce qui signifie qu'elle traite les données soumises dans le formulaire.
        $form->handleRequest($request);

        # Utilise le service "formationRepository" pour trouver une formation en fonction de son identifiant "$formation_id".
        $formation = $formationRepository->find($formation_id);
              
        # Cette condition vérifie si le formulaire a été soumis et si ses données sont valides.
        if ($form->isSubmitted() && $form->isValid()) { 

            // on récupère les données du formulaire
            $session = $form->getData(); 

            // prepare PDO
            $entityManager->persist($session); 

            // execute PDO
            $entityManager->flush(); 

            # On redirige l'utilisateur vers la route 'app_session'.
            return $this->redirectToRoute('app_session');

        }

        # Rend un modèle Twig appelé 'session/new.html.twig'.
        # Elle passe également le formulaire et l'ID de la session en cours d'édition à la vue.
        return $this->render('session/new.html.twig', [
            'form' => $form->createView(), // Envoie le formulaire à la vue
            'edit' => $session->getId(),
			'sessionId' => $session->getId()
        ]);
        
    }



    #[Route('/admin/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager)
    {
        
        // remove : prépare la requête
        $entityManager->remove($session);
        // Faire la requête SQL DELETE FROM
        $entityManager->flush();

        return $this->redirectToRoute('app_session'); 

    }

    #[Route('/admin/{stagiaire_id}/session/{id}', name: 'add_session')]
    public function addStagiaireToSession(StagiaireRepository $stagiaireRepository, EntityManagerInterface $entityManager, Session $session, $stagiaire_id)
    {

        $stagiaire = $stagiaireRepository->find($stagiaire_id);

        if ($stagiaire) {

            // Ajoutez le stagiaire à la session
            $session->addStagiaire($stagiaire);

            // Enregistrez les modifications dans la base de données
            $entityManager->persist($session); // Prépare l'insertion en base de données
            
            $entityManager->flush(); // Exécute l'insertion en base de données

            // Redirigez l'utilisateur vers la page actuelle (ou une autre page si nécessaire)
            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);

        }

    }


    #[Route('/admin/remove/{stagiaire_id}/session/{id}', name: 'remove_session')]
    public function removeStagiaireToSession(StagiaireRepository $stagiaireRepository, EntityManagerInterface $entityManager, Session $session, Request $request, $stagiaire_id)
    {

        $stagiaire = $stagiaireRepository->find($stagiaire_id);

        if ($stagiaire) {

            // Retirer le stagiaire de la session
            $session->removeStagiaire($stagiaire);
            
            // Enregistrez les modifications dans la base de données
            $entityManager->persist($session); // Prépare l'insertion en base de données
            
            $entityManager->flush(); // Exécute l'insertion en base de données
            // Redirigez l'utilisateur vers la page actuelle (ou une autre page si nécessaire)
            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);

        }

    }


    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository): Response 
    {

        $programmes = $session->getProgrammes();
        $stagiaires = $session->getStagiaires();
        
        $stagiaireNotInSession = $sessionRepository->findByStagiairesNotInSession($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'programmes' => $programmes,
            'stagiaireNotInSession' => $stagiaireNotInSession,
        ]);

    }

}
