<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{

    private $mailer;

    public function __construct(MailerInterface $mailer, UserRepository $userRepository) 
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        
        $context = $email->getContext();

        $email->context($context);

        $this->mailer->send($email);
    }

  
    /*public function verifyEmailConfirmation($token): void
    {
        $user = $this->userRepository->findOneBy(['validationToken' => $token]);

        if(empty($user) || $user == null) {
            throw new Exception('Erreur');
        }
        else {
            $user->setIsVerified(true);
            $user->setValidationToken(null);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }*/
}
