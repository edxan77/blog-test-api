<?php

namespace App\Repositories\User;

use App\Models\ProfileConfirm;

interface IProfileConfirmationRepository
{
    public function create(array $data): ProfileConfirm;
    public function update(ProfileConfirm $profileConfirm, array $updateData): ProfileConfirm;
}
