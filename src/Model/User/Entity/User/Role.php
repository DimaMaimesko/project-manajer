<?php

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class Role
{
    public const ADMIN = 'ROLE_ADMIN';
    public const USER = 'ROLE_USER';

    private $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [self::ADMIN, self::USER]);

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function isEqual(Role $role): bool
    {
        return $this->name === $role->name;
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->name;
    }

}
