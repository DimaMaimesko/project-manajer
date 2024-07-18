<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Members\Group;

use App\Model\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepository extends ServiceEntityRepository
{

    public function __construct(
        ManagerRegistry $registry,
        protected EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, Group::class);
    }

    public function get(Id $id): Group
    {
        /** @var Group $group */
        if (!$group = $this->find($id->getValue())) {
            throw new EntityNotFoundException('Group is not found.');
        }
        return $group;
    }

    public function add(Group $group): void
    {
        $this->entityManager->persist($group);
    }

    public function remove(Group $group): void
    {
        $this->entityManager->remove($group);
    }
}
