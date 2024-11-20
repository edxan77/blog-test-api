<?php

namespace App\Services\Mail;

use App\Models\ProfileConfirm;

interface IMailService
{
    public function send(array $data, ProfileConfirm $profileConfirm);
}
