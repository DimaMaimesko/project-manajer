<?php

namespace App\Tests\Model\User\UseCase\SignUp;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{




    public function testSuccess(): void
    {
        $user = new User(
            $id = Id::next(),
            $date = new \DateTimeImmutable("now"),
        );
        $user->signUpByEmail(
            $email= new Email('test@test.com'),
            $hash = 'hash',
            $token = 'token');

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getHashedPassword());
        self::assertEquals($token, $user->getConfirmationToken());

    }
}
