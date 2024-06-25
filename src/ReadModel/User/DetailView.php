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
    public $first_name;
    public $last_name;
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
        $this->first_name = $user['first_name'] ?? null;
        $this->last_name = $user['last_name'] ?? null;

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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
    }
}
