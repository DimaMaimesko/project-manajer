<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail  as SymfonyEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetTokenSender
{
    public function __construct(
        protected MailerInterface $mailer,
        protected UrlGeneratorInterface $router
    )
    {
    }

    public function send(Email $email, string $token): void
    {
        $resetLink = $this->router->generate(
            'auth.reset.reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL
        );

        $symfonyEmail = (new SymfonyEmail())
            ->from('hello@example.com')
            ->to($email->getValue())
            ->subject('Password resetting')
            ->htmlTemplate('emails/user/reset.html.twig')
            ->context([
                'resetLink' => $resetLink
            ]);

        try {
            $this->mailer->send($symfonyEmail);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

    }

}
