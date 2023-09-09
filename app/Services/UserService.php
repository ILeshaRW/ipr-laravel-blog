<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected UserRepository $repository)
    {}

    public function create(array $fields): User
    {
        $fields['password'] = Hash::make($fields['password']);

        return $this->repository->create($fields);
    }
}
