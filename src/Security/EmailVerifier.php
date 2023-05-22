<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailVerifier
{
    private $mailer;
    private $manager;

    public function __construct(
        MailerInterface $mailer, 
        UserRepository $userRepository, 
        EntityManagerInterface $manager
    ) {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        $context = $email->getContext();

        $email->context($context);

        $this->mailer->send($email);
    }

    public function verifyEmailConfirmation(User $user): void
    {
        $user->setIsVerified(true);
        $user->setValidationToken(null);
        $this->manager->persist($user);
        $this->manager->flush();
    }
}
