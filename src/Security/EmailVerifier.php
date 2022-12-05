<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        
        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();

        $email->context($context);

        $this->mailer->send($email);
    }

  
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {

        //$user->setIsVerified(true);

        //$this->entityManager->persist($user);
        //$this->entityManager->flush();
    }
}
