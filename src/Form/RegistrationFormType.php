<?php

namespace App\Form;

use App\Entity\Formateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
                        'message' => 'Le format de l\'adresse électronique n\'est pas valide. Veuillez utiliser une adresse électronique valide.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter le mot de passe'],
                'constraints' => [
                    // Contrainte 1: NotBlank
                    new NotBlank([
                        'message' => 'Cette valeur ne doit pas être vide.',
                    ]),
                    // Contrainte 2: Regex (expression régulière)
                    // Contrainte pour un mot de passe : 
                    // Au moins une lettre majuscule, une lettre minuscule, un chiffre de 0-9, un caractère spécial (#?!@$%^&*-), et 12 caractères
                    new Regex([
                        'pattern' => '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/',
                        'message' => 'Le mot de passe doit contenir au moins lettre majuscule, une lettre minuscule, un chiffre de 0-9, un caractère spécial (#?!@$%^&*-), et 12 caractères.'
                    ]),
                ],
            ])
            ->add('honeypot', HiddenType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'style' => 'display:none'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formateur::class,
        ]);
    }
}
