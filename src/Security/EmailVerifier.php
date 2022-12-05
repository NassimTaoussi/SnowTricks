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

    private $mailer;

    public function __construct(MailerInterface $mailer) 
    {
        $this->mailer = $mailer;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        
        $context = $email->getContext();
        //$context['signedUrl'] = $signatureComponents->getSignedUrl();

        $email->context($context);

        $this->mailer->send($email);
    }

  
    public function verifyEmailConfirmation(): void
    {

        //$user->setIsVerified(true);
        //$this->entityManager->persist($user);
        //$this->entityManager->flush();
    }
}
