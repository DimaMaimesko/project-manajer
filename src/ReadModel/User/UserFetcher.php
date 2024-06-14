<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\FetchMode;

class UserFetcher
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function existsByResetToken(string $token): bool
    {
        if(empty($token)){
            throw new InvalidArgumentException("Token must not be empty.");
        }
        $query = $this->connection->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('user_users')
            ->where('reset_token_token = :token')
            ->setParameter('token', $token)
            ->executeQuery();

        return $query->fetchOne() > 0;
    }

    /**
     * @throws Exception
     */
    public function findByEmail(string $email): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('user_users')
            ->where('email = :email')
            ->setParameter('email', $email);

        $user = $stmt->executeQuery()->fetchAssociative();

        return $user;
    }
}