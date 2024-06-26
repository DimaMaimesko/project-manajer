<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use App\Model\User\Entity\User\Id;
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

    /**
     * @throws Exception
     */
    public function findDetail(Id $id): ?DetailView
    {
        $user = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'date',
                'email',
                'name_first first_name',
                'name_last last_name',
                'email',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('id = :id')
            ->setParameter('id', $id->getValue())
            ->executeQuery();

        $user = $user->fetchAssociative();
        if ($user) {
            $detailView = new DetailView($user);
        }


        $networks = $this->connection->createQueryBuilder()
            ->select('network', 'identity')
            ->from('user_user_networks')
            ->where('user_id = :id')
            ->setParameter('id', $id->getValue())
            ->executeQuery();

        $networks = $networks->fetchAssociative();



        $detailView->networks = $networks;

        return $detailView;
    }

    public function getDetail(Id $id): DetailView
    {
        if (!$detail = $this->findDetail($id)) {
            throw new \LogicException('User is not found');
        }
        return $detail;
    }

    /**
     * @throws Exception
     */
    public function all(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'date',
                'TRIM(CONCAT(name_first, \' \', name_last)) AS name',
                'email',
                'role',
                'status'
            )
            ->from('user_users')
            ->orderBy('date', 'desc')
            ->executeQuery();

        return $stmt->fetchAllAssociative();
    }
}
