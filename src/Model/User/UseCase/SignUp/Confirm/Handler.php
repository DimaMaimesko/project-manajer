<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm;
use App\Model\User\Entity\UserRepository;
use App\Model\Flusher;

class Handler
{
    public function __construct(
        protected UserRepository $repository,
        protected Flusher $flusher
    )
    {
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->repository->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect or confirmed token.');
        }

        $user->confirmSignUp();

        $this->flusher->flush();
    }
}
