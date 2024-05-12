<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\User;
interface UserRepository
{
    public function hasByEmail(Email $email): bool;

    public function add(User $user): void;

    public function findByConfirmToken(User $user): User;
}
