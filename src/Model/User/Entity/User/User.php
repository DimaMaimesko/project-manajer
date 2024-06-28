<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use App\Model\User\Service\PasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(
    name: 'user_users',
    uniqueConstraints: [
    new ORM\UniqueConstraint(columns: ['email']),
    new ORM\UniqueConstraint(columns: ['reset_token_token']),
]
),
    ORM\Index(name: 'IDX_USER_USER_ID', columns: ['id'])
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private const STATUS_NEW = 'new';
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    #[ORM\Column(type: 'user_user_id'), ORM\Id]
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $date;

    #[ORM\Column(type: 'user_user_email', unique: true, nullable: true)]
    private $email;

    #[ORM\Column(name: 'new_email',type: 'user_user_email', unique: true, nullable: true)]
    private $newEmail;

    #[ORM\Column(name: 'password_hash', type: 'string', length: 255, nullable: true)]
    private $passwordHash;

    #[ORM\Column(name: 'confirm_token', type: 'string', length: 255, nullable: true)]
    private $confirmToken;

    #[ORM\Column(name: 'new_email_confirm_token', type: 'string', length: 255, nullable: true)]
    private $newEmailConfirmToken;

    #[ORM\Embedded(class: ResetToken::class, columnPrefix: 'reset_token_')]
    private $resetToken;

    #[ORM\Column(type: 'string', length: 16)]
    private string $status;

    /**
     * @var Network[]|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Network::class, mappedBy: 'user', cascade: ['persist'], orphanRemoval: true)]
    private $networks;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private $role;


    #[ORM\Embedded(class: 'Name')]
    private $name;

    private function __construct(Id $id, \DateTimeImmutable $date, Name $name)
    {
        $this->id = $id;
        $this->date = $date;
        $this->name = $name;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    public static function signUpByEmail(Id $id, \DateTimeImmutable $date, Name $name, Email $email, string $hash, string $token): self
    {
        $user = new self($id, $date, $name);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = self::STATUS_WAIT;
        return $user;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public static function signUpByNetwork(Id $id, \DateTimeImmutable $date, string $network, string $identity): self
    {
        $user = new self($id, $date, new Name('Test', 'User'));
        $user->attachNetwork($network, $identity);
        $user->status = self::STATUS_ACTIVE;
        return $user;
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

    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }
        if (!$this->email) {
            throw new \DomainException('Email is not specified.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting is already requested.');
        }
        $this->resetToken = $token;
    }

    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Resetting is not requested.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired.');
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role !== null && $this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }
        $this->role = $role;
    }

    public function requestEmailChanging(Email $email, string $token): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }
        if ($this->email && $this->email->isEqual($email)) {
            throw new \DomainException('Email is already same.');
        }
        $this->newEmail = $email;
        $this->newEmailConfirmToken = $token;
    }

    public function confirmEmailChanging(string $token): void
    {

        if (!$this->newEmailConfirmToken) {
            throw new \DomainException('Changing is not requested.');
        }

        if ($this->newEmailConfirmToken !== $token) {
            throw new \DomainException('Incorrect changing token.');
        }

        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailConfirmToken = null;
    }


    public function getRole()
    {
        return $this->role;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function isPasswordCorrect($password): bool
    {
        return true;
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

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken?->getToken();
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return $this->email->getValue();
    }

    public function getPassword(): ?string
    {
        // TODO: Implement getPassword() method.
        return $this->passwordHash;
    }

    public function getNewEmailToken(): ?string
    {
        return $this->newEmailConfirmToken;
    }

    public function getNewEmail()
    {
        return $this->newEmail;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
