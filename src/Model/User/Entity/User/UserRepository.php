<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use App\Model\User\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    private EntityRepository $repo;
    public function __construct(
        protected EntityManagerInterface $entityManager,
    )
    {
        $this->repo = $this->entityManager->getRepository(User::class);

    }

    /**
     * @param  string  $token
     * @return User|null
     */
    public function findByConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['confirmToken' => $token]);
    }

    /**
     * @param  string  $token
     * @return User|null
     */
    public function findByResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['token' => $token]);
    }

    public function get(Id $id): User
    {
        if (!$user = $this->repo->find($id->getValue())) {
            throw new \App\Model\EntityNotFoundException('User is not found.');
        }
        return $user;
    }

    /**
     * @param  Email  $email  - The email address to search for.
     * @return User - The user object if found.
     * @throws EntityNotFoundException - If user is not found.
     */
    public function getByEmail(Email $email): User
    {
        if (!$user = $this->repo->findOneBy(['email' => $email->getValue()])) {
            throw new EntityNotFoundException('User is not found.');
        }
        return $user;
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function hasByNetworkIdentity(string $network, string $identity): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->innerJoin('t.networks', 'n')
                ->andWhere('n.network = :network and n.identity = :identity')
                ->setParameter(':network', $network)
                ->setParameter(':identity', $identity)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
    }


}
