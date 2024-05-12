<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Auth;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Entity\User;

class Handler
{

    public function __construct(
        protected UserRepository $userRepository,
        protected Flusher $flusher
    )
    {
    }

    public function handle(Command $command): void
    {
        if ($this->userRepository->hasByNetworkIdentity($command->network, $command->identity)) {
            throw new \DomainException('User already exists.');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpByNetwork(
            $command->network,
            $command->identity
        );

        $this->users->add($user);

        $this->flusher->flush();
    }
}
