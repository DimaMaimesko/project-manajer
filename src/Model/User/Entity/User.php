<?php

namespace App\Model\User\Entity;

class User
{
    private const string STATUS_WAIT = 'wait';
    private const string STATUS_ACTIVE = 'active';


    public function __construct(
        protected Id $id,
        protected \DateTimeImmutable $date,
        protected Email $email,
        protected string $hashedPassword,
        protected string $confirmationToken,
        protected $status = self::STATUS_WAIT,
    )
    {

    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

}
