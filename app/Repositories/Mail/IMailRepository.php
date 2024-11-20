<?php

namespace App\Repositories\Mail;

use App\Models\Mail;

interface IMailRepository
{
    public function create(array $data): Mail;
}
