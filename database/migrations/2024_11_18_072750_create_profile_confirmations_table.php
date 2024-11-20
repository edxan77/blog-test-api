<?php

use App\Models\ProfileConfirm;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_confirmations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('email');
            $table->string('token')->nullable();
            $table->enum('status',[ProfileConfirm::TOKEN_STATUS_EXPIRED, ProfileConfirm::TOKEN_STATUS_VALID]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_confirmations');
    }
};
