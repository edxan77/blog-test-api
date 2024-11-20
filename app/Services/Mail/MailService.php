<?php

namespace App\Services\Mail;

use App\Models\ProfileConfirm;
use App\Repositories\Mail\IMailRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Mail as BlogMail;

class MailService implements IMailService
{
    public function __construct(
        private IMailRepository $mailRepository
    ){}

    public function send(array $data, ProfileConfirm $profileConfirm)
    {
        $mailStoreData = [
            'to' => $data['email'],
            'from' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'subject' => env('MAIL_SUBJECT'),
            'body' => $data['body'],
        ];

        $mail = $this->mailRepository->create($mailStoreData);

        try {
            Mail::send('mail.index', ['token' => $profileConfirm->token], function ($m) use ($data) {
                $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $m->to($data['email'])->subject(env('MAIL_SUBJECT'));
            });

            $mail->status = BlogMail::STATUS_SENT;
        } catch (\Exception $e) {
            Log::error('mail_service.send.' . $e->getMessage());

            $mail->status = BlogMail::STATUS_FAILED;
        }

        $mail->save();
    }
}
