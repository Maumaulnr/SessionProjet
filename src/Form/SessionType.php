<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use App\Form\ProgrammeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSession', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('placesTheoriques', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('programmes', CollectionType::class, [
                // la collection attend l'élément qu'elle entrera dans le form
                // ce n'est pas obligatoire que ce soit un autre form
                'entry_type' => ProgrammeType::class,
                'prototype' => true,
                // on va autoriser l'ajout d'un nouvel élément dans l'entité Session qui seront persisté grâce au cascade_persist sur l'élément Programme
                // ça va activer un data prototype qui sera un attribut HTML qu'on pourra manipuler en JS
                // allow_add permet d'ajouter plusieurs programmes et allow_delete de supprimer des éléments
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // il est obligatoire car Session n'a pas de setProgramme mais c'est Programme qui contient setSession
                // c'est Programme qui est propiétaire de la relation (éviter un mapping false on rajoute by_reference => false)
            ])
            ->add('stagiaires', CollectionType::class, [
                // la collection attend l'élément qu'elle entrera dans le form
                // ce n'est pas obligatoire que ce soit un autre form
                'entry_type' => StagiaireType::class,
                'prototype' => true,
                // on va autoriser l'ajout d'un nouvel élément dans l'entité Session qui seront persisté grâce au cascade_persist sur l'élément Stagiaire
                // ça va activer un data prototype qui sera un attribut HTML qu'on pourra manipuler en JS
                // allow_add permet d'ajouter plusieurs stagiaires et allow_delete de supprimer des éléments
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // il est obligatoire car Session n'a pas de setStagiaire mais c'est Stagiaire qui contient setSession
                // c'est Stagiaire qui est propiétaire de la relation (éviter un mapping false on rajoute by_reference => false)
            ])
            // ->add('stagiaires', EntityType::class, [
            //     'class' => Stagiaire::class,
            //     'attr' => [
            //         'class' => 'form-control'
            //     ],
            //     'multiple' => true, // Permet la sélection de plusieurs stagiaires
            //     'expanded' => true, // Utilisez des cases à cocher
            //     'choice_label' => 'nom', // Le champ à afficher dans les options
            // ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
