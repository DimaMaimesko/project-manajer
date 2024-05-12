<?php

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\ConfirmTokenizer;
use App\Model\User\Service\ConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\User\Entity\User;
use Ramsey\Uuid\Uuid;

class Handler
{
    public function __construct(
        protected UserRepository $repository,
        protected PasswordHasher $passwordHasher,
        protected Flusher $flusher,
        protected ConfirmTokenizer $confirmTokenizer,
        protected ConfirmTokenSender $confirmTokenSender
    ){
    }


    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->repository->hasByEmail($email)) {
            throw new \DomainException('User already exists.');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable(),
            $email,
            $this->passwordHasher->hash($command->password),
            $this->confirmTokenizer->generate()
        );

        $this->repository->add($user);

        $this->confirmTokenSender->send($user);

        $this->flusher->flush();
    }


}
