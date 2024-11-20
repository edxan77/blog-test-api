<?php

namespace App\Repositories\User;

use App\Models\ProfileConfirm;

class ProfileConfirmationRepository implements IProfileConfirmationRepository
{
    public function create(array $data): ProfileConfirm
    {
        $profileConfirm = new ProfileConfirm($data);
        $profileConfirm->save();

        return $profileConfirm;
    }

    public function update(ProfileConfirm $profileConfirm, array $updateData): ProfileConfirm
    {
        $profileConfirm->update($updateData);

        return $profileConfirm;
    }
}
