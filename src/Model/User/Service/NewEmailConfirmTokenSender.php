<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail  as SymfonyEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewEmailConfirmTokenSender
{
    public function __construct(
        protected MailerInterface $mailer,
        protected UrlGeneratorInterface $router
    )
    {
    }

    public function send(Email $email, string $token): void
    {
        $symfonyEmail = (new SymfonyEmail())
            ->from('hello@example.com')
            ->to($email->getValue())
            ->subject('Email Confirmation')
            ->htmlTemplate('emails/user/email.html.twig')
            ->context([
                'token' => $token,
            ]);

       $this->mailer->send($symfonyEmail) ;


    }
}



