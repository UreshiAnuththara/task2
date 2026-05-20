<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds login_start and login_end columns to user_roles table.
 * These define the allowed login window for all users assigned to that role.
 * null = no restriction (24-hour access).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_roles', function (Blueprint $table) {
            if (! Schema::hasColumn('user_roles', 'login_start')) {
                $table->string('login_start', 5)->nullable()->after('description')
                      ->comment('HH:MM — start of allowed login window');
            }
            if (! Schema::hasColumn('user_roles', 'login_end')) {
                $table->string('login_end', 5)->nullable()->after('login_start')
                      ->comment('HH:MM — end of allowed login window');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_roles', function (Blueprint $table) {
            foreach (['login_start', 'login_end'] as $col) {
                if (Schema::hasColumn('user_roles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};