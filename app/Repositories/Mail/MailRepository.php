<?php

namespace App\Repositories\Mail;

use App\Models\Mail;

class MailRepository implements IMailRepository
{

    public function create(array $data): Mail
    {
        $mail = new Mail($data);
        $mail->sent_date = now();
        $mail->status = Mail::STATUS_PENDING;
        $mail->save();

        return $mail;
    }
}
