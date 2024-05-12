<?php

namespace App\Model\User\Entity;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\Network;
use App\Model\User\Entity\Id;
use Doctrine\Common\Collections\ArrayCollection;

class User
{
    private const string STATUS_WAIT = 'wait';
    private const string STATUS_ACTIVE = 'active';
    private const string STATUS_NEW = 'new';

    /**
     * @var Id
     */
    private $id;
    /**
     * @var \DateTimeImmutable
     */
    private $date;
    /**
     * @var Email|null
     */
    private $email;
    /**
     * @var string|null
     */
    private $passwordHash;
    /**
     * @var string|null
     */
    private $confirmToken;
    /**
     * @var string
     */
    private $status;
    /**
     * @var Network|ArrayCollection
     */
    private $networks;

    public function __construct(
        Id $id,
        \DateTimeImmutable $date,
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->status = self::STATUS_NEW;
        $this->networks = new ArrayCollection();
    }

    public function signUpByEmail(Email $email, string $hash, string $token): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }

    public function signUpByNetwork(string $network, string $identity): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }
        $this->attachNetwork($network, $identity);
        $this->status = self::STATUS_ACTIVE;
    }

    private function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    public function  confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
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
        return $this->passwordHash;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmToken;
    }

    /**
     * @return \App\Model\User\Entity\User\Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

}
