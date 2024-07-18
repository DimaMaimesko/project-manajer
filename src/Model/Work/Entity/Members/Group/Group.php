<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Members\Group;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(
    name: 'work_members_groups'
)]
class Group
{

    #[ORM\Column(type: 'work_members_group_id'), ORM\Id]
    private $id;

    #[ORM\Column(type: 'string')]
    private $name;

    public function __construct(Id $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function edit(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
