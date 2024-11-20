<?php

namespace App\Repositories\User;

use App\Models\User;

interface IUserRepository
{
    public function create(array $data): User;
    public function update(User $user, array $updateData): User;
}
