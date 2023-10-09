<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    # Constructeur de la classe pour injecter le service EmailVerifier
    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    # Action pour gérer l'inscription d'un utilisateur
    #[Route('/admin/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Formateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # Vérification du champ honeypot
            $honeypot = $form->get('honeypot')->getData();
            if (empty($honeypot)) {
                
                # Encodage du mot de passe en utilisant UserPasswordHasherInterface
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->setRoles(["ROLE_ADMIN"]);
    
                $entityManager->persist($user);
                $entityManager->flush();
    
                # Génération d'une URL signée et envoi d'un email de confirmation
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('admin@exemple.com', 'Admin Site'))
                        ->to($user->getEmail())
                        ->subject('S\'il vous plaît confirmer votre email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                # Authentification de l'utilisateur après son inscription
    
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );

            } else {
                # Redirection en cas de tentative de bot
                return $this->redirectToRoute('app_register');

            }

        }

        # Affichage du formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    }

    # Action pour vérifier l'email de l'utilisateur
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        # Validation du lien de confirmation d'email, définit User::isVerified=true et persiste
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        # @TODO Modifiez la redirection en cas de succès et gérez ou supprimez le message flash dans vos modèles.
        $this->addFlash('success', 'Votre email a bien été vérifié.');

        return $this->redirectToRoute('app_register');
    }

}
