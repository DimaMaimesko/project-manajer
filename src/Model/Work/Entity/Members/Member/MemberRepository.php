<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Members\Member;

use App\Model\EntityNotFoundException;
use App\Model\Work\Entity\Members\Group\Id as GroupId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class MemberRepository extends ServiceEntityRepository
{

    public function __construct(
        ManagerRegistry $registry,
        protected EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, Member::class);
    }

    public function has(Id $id): bool
    {
        return $this->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.id = :id')
                ->setParameter('id', $id->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function get(Id $id): Member
    {
        /** @var Member $member */
        if (!$member = $this->find($id->getValue())) {
            throw new EntityNotFoundException('Member is not found.');
        }
        return $member;
    }

    public function add(Member $member): void
    {
        $this->entityManager->persist($member);
    }

    public function hasByGroup(GroupId $id): bool
    {
        return $this->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.group = :group')
                ->setParameter(':group', $id->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }
}
