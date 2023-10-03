<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Session;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbJours', IntegerType::class, [
                'label' => 'Durée en jours',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 100,
                ]
            ])
            ->add('cours', EntityType::class, [
                'label' => 'Cours',
                'class' => Cours::class,
                'choice_label' => 'nomModule',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // sera lié à la session que l'on édite donc on le cache
            // ->add('session', HiddenType::class, [
            //     'mapped' => false,
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            // ])
            // ->add('session', EntityType::class, [
            //     'class' => Session::class,
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            // ])
            // ->add('valider', SubmitType::class, [
            //     'attr' => [
            //         'class' => 'btn btn-success'
            //     ]
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
