<?php

use App\Models\Mail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('confirmation_emails', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('from_name');
            $table->string('to');
            $table->string('subject', 1000);
            $table->text('body');
            $table->dateTime('sent_date');
            $table->enum('status', [Mail::STATUS_PENDING, Mail::STATUS_SENT, Mail::STATUS_FAILED]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
