<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type'); // 'trial_30m', 'trial_1h', 'custom_minutes', 'custom_days', 'lifetime'
            $table->integer('duration_minutes')->nullable();
            $table->string('client_name')->nullable();
            $table->string('status')->default('unused'); // 'unused', 'used', 'revoked'
            $table->timestamp('used_at')->nullable();
            $table->string('used_by_email')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
