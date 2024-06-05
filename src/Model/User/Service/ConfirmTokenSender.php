<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;
class ConfirmTokenSender
{
    public function __construct(
        protected MailerInterface $mailer,
    )
    {
    }

    public function send(Email $email, string $token): void
    {
        $symfonyEmail = (new SymfonyEmail())
            ->from('hello@example.com')
            ->to($email->getValue())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->setBody()
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($symfonyEmail);

    }
}
