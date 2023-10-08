<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{

    # Indique que cette fonction est associée à la route '/categorie' et qu'elle est nommée 'app_categorie'.
    # Lorsque cette route est appelée, cette fonction sera exécutée.
    #[Route('/categorie', name: 'app_categorie')]
    # Ceci est la signature de la fonction "index". Elle prend un paramètre "$categorieRepository" de type "CategorieRepository".
    # Le paramètre "$categorieRepository" est injecté automatiquement par Symfony grâce à l'injection de dépendances.
    public function index(CategorieRepository $categorieRepository): Response
    {

        # Utilise le "$categorieRepository" pour récupérer toutes les catégories de la base de données.
        # Les catégories sont triées par ordre alphabétique croissant en fonction de leur nom.
        $categories = $categorieRepository->findBy([], ["nomCategorie" => "ASC"]);

        # Renvoie une réponse HTTP à l'utilisateur.
        # En utilisant "render", on génère la vue Twig associée au template 'categorie/index.html.twig'.
        # On passe également les données des catégories à la vue pour qu'elle puisse les afficher.
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);

    }


    # Indique que cette fonction est associée à deux routes : '/categorie/new' et '/categorie/{id}/edit'.
    # Elle est nommée 'new_categorie' pour la première route et 'edit_categorie' pour la deuxième route.
    # Lorsque l'une de ces routes est appelée, cette fonction sera exécutée.
    #[Route('/categorie/new', name: 'new_categorie')]
    #[Route('/categorie/{id}/edit', name: 'edit_categorie')]
    # Ceci est la signature de la fonction "new_edit". Elle prend un paramètre "$categorie" de type "Categorie" avec une valeur par défaut nulle.
    # Elle prend également un paramètre "$request" de type "Request" pour représenter la requête HTTP entrante.
    # Et enfin, elle prend un paramètre "$entityManager" de type "EntityManagerInterface" pour la gestion des entités.
    public function new_edit(Categorie $categorie = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        # Cette condition vérifie si "$categorie" est nulle. Si c'est le cas, elle crée une nouvelle instance de "Categorie".
        if(!$categorie) {
            $categorie = new Categorie();
        }

        # Crée un formulaire en utilisant le type de formulaire "CategorieType" et l'objet "$categorie".
        $form = $this->createForm(CategorieType::class, $categorie);

        # Gère la requête HTTP entrante, ce qui signifie qu'elle traite les données soumises dans le formulaire.
        $form->handleRequest($request);

        # Cette condition vérifie si le formulaire a été soumis et si ses données sont valides.
        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère les données du formulaire
            $categorie = $form->getData();
            // prepare PDO
            $entityManager->persist($categorie);
            // execute PDO
            $entityManager->flush();

            # On redirige l'utilisateur vers la route 'app_categorie'.
            return $this->redirectToRoute('app_categorie');

        }

        # Rend un modèle Twig appelé 'categorie/new.html.twig'.
        # Elle passe également le formulaire et l'ID de la catégorie en cours d'édition à la vue.
        return $this->render('categorie/new.html.twig', [
            'formAddCategorie' => $form,
            'edit' => $categorie->getId(),
        ]);

    }


    # Indique que cette fonction est associée à la route '/categorie/{id}/delete' et qu'elle est nommée 'delete_categorie'.
    # Lorsque cette route est appelée, cette fonction sera exécutée.
    #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
    # Ceci est la signature de la fonction "delete". Elle prend un paramètre "$categorie" de type "Categorie".
    # Le paramètre "$categorie" est injecté automatiquement par Symfony en fonction de la valeur de "{id}" dans l'URL.
    # Elle prend également un paramètre "$entityManager" de type "EntityManagerInterface" pour la gestion des entités.
    public function delete(Categorie $categorie, EntityManagerInterface $entityManager)
    {
        
        # Prépare la requête pour supprimer l'entité "$categorie".
        $entityManager->remove($categorie);

        # Exécute la requête SQL DELETE FROM pour supprimer l'entité de la base de données.
        $entityManager->flush();

        # Redirige l'utilisateur vers la route 'app_categorie' après la suppression.
        return $this->redirectToRoute('app_categorie'); 

    }


    # Indique que cette fonction est associée à la route '/categorie/{id}' et qu'elle est nommée 'show_categorie'.
    # Lorsque cette route est appelée, cette fonction sera exécutée.
    #[Route('/categorie/{id}', name: 'show_categorie')]
    # Ceci est la signature de la fonction "show". Elle prend un paramètre "$categorie" de type "Categorie".
    # Le paramètre "$categorie" est injecté automatiquement par Symfony en fonction de la valeur de "{id}" dans l'URL.
    public function show(Categorie $categorie): Response 
    {

        # Renvoie une réponse HTTP à l'utilisateur.
        # En utilisant "render", on génère la vue Twig associée au template 'categorie/show.html.twig'.
        # On passe également l'objet "$categorie" à la vue pour qu'elle puisse l'afficher.
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie
        ]);

    }

}
