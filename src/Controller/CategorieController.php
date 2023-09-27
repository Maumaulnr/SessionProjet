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

    #[Route('/categorie', name: 'app_categorie')]
    // public function index(): Response
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findBy([], ["nomCategorie" => "ASC"]);
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/categorie/new', name: 'new_categorie')]
    #[Route('/categorie/{id}/edit', name: 'edit_categorie')]
    public function new_edit(Categorie $categorie = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$categorie) {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère les données du formulaire
            $categorie = $form->getData();
            // prepare PDO
            $entityManager->persist($categorie);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie');

        }

        return $this->render('categorie/new.html.twig', [
            'formAddCategorie' => $form,
            'edit' => $categorie->getId(),
        ]);
    }


    #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
    public function delete(Categorie $categorie, EntityManagerInterface $entityManager)
    {
        
        // remove : prépare la requête
        $entityManager->remove($categorie);
        // Faire la requête SQL DELETE FROM
        $entityManager->flush();

        return $this->redirectToRoute('app_categorie'); 

    }

    #[Route('/categorie/{id}', name: 'show_categorie')]
    public function show(Categorie $categorie): Response 
    {

        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie
        ]);

    }

}
