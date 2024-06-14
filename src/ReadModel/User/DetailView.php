<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use App\Model\User\Entity\User\User;

class DetailView
{
    public $id;
    public $date;
    public $email;
    public $role;
    public $status;
    /**
     * @var NetworkView[]
     */
    public $networks;

    public function __construct(array $user)
    {
        $this->id = $user['id'] ?? null;
        $this->date = $user['date'] ?? null;
        $this->email = $user['email'] ?? null;
        $this->role = $user['role'] ?? null;
        $this->status = $user['status'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'networks' => $this->networks,
        ];
    }
}
