<?php

namespace App\Tests\Model\User;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $id = Id::next(),
            $date = new \DateTimeImmutable("now"),
        );

        self::assertTrue($user->isNew());
        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());
    }

}
