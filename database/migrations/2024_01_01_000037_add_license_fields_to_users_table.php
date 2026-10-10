<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'license_code')) {
                $table->string('license_code')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'license_type')) {
                $table->string('license_type')->nullable()->after('license_code');
            }
            if (!Schema::hasColumn('users', 'license_expires_at')) {
                $table->timestamp('license_expires_at')->nullable()->after('license_type');
            }
            if (!Schema::hasColumn('users', 'license_activated_at')) {
                $table->timestamp('license_activated_at')->nullable()->after('license_expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['license_code', 'license_type', 'license_expires_at', 'license_activated_at']);
        });
    }
};
