<?php

namespace App\Tests\Model\User\UseCase\SignUp;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{




    public function testSuccess(): void
    {
        $user = $this->createUser();

        $user->confirmSignUp();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getConfirmationToken());

    }

    public function testAlreadyConfirmed(): void
    {
        $user = $this->createUser();

        $user->confirmSignUp();
        $this->expectExceptionMessage('User is already confirmed.');
        $user->confirmSignUp();
    }

    /**
     * @return User
     */
    public function createUser(): User
    {
        $user = new User(
            Id::next(),
            new \DateTimeImmutable(),
        );

        $user->signUpByEmail(
            new Email('dima.maimesko@gmail.com'),
            'hash',
            'token'
        );

        return $user;
    }
}
