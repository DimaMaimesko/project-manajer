<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail  as SymfonyEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SignUpConfirmTokenSender
{
    public function __construct(
        protected MailerInterface $mailer,
        protected UrlGeneratorInterface $router
    )
    {
    }

    public function send(Email $email, string $token): void
    {
        $confirmationLink = $this->router->generate(
            'auth.signup.confirm', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL
        );

        $symfonyEmail = (new SymfonyEmail())
            ->from('hello@example.com')
            ->to($email->getValue())
            ->subject('Confirm Your Email Address')
            ->htmlTemplate('emails/confirmation.html.twig')
            ->context([
                'confirmation_link' => $confirmationLink,
            ]);

        $this->mailer->send($symfonyEmail);

    }
}
