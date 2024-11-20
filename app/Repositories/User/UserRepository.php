<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements IUserRepository
{
    public function create(array $data): User
    {
        $user = new User($data);
        $user->save();

        return $user;
    }

    public function update(User $user, array $updateData): User
    {
        $user->update($updateData);

        return $user;
    }

}
