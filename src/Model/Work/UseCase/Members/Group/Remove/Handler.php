<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Members\Group\Remove;

use App\Model\Flusher;
use App\Model\Work\Entity\Members\Group\GroupRepository;
use App\Model\Work\Entity\Members\Group\Id;
use App\Model\Work\Entity\Members\Member\MemberRepository;

class Handler
{
    private $groups;
    private $flusher;

    public function __construct(GroupRepository $groups, protected MemberRepository $memberRepository, Flusher $flusher)
    {
        $this->groups = $groups;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $group = $this->groups->get(new Id($command->id));

        if ($this->memberRepository->hasByGroup($group->getId())) {
            throw new \DomainException('Group is not empty.');
        }

        $this->groups->remove($group);

        $this->flusher->flush();
    }
}
