<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/forgot_password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request, 
        UserRepository $usersRepository, 
        EntityManagerInterface $entityManager, 
        EmailVerifier $emailVerifier
    ) {
        $form = $this->createForm(ResetPasswordRequestFormType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            if ($user) {
                if (true == $user->isVerified()) {
                    $token = Uuid::v4();
                    $user->setValidationToken($token);
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $url = $this->generateUrl(
                        'reset_password', 
                        ['token' => $token], 
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );

                    $emailVerifier->sendEmailConfirmation(
                        'app_reset_pass',
                        $user,
                        (new TemplatedEmail())
                            ->from(new Address('nassim-taoussi@hotmail.com', 'SnowTricks'))
                            ->to($user->getEmail())
                            ->subject('Demande de réinitialisation de mot de passe')
                            ->htmlTemplate('security/request_password_email.html.twig')
                            ->context([
                                'url' => $url,
                                'user' => $user,
                            ])
                    );
                }
            }
            $this->addFlash('success', 'Email envoyé avec succès');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{validationToken}', name: 'reset_password')]
    public function resetPassword(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(ResetPasswordFormType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setValidationToken(null);
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Mot de passe changé avec succès');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'passForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
