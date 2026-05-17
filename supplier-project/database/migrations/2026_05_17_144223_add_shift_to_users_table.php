<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'shift')) {
                // 'day' = 08:00–18:00, 'night' = 18:00–08:00, null = no restriction (admin)
                $table->string('shift')->nullable()->after('profile_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'shift')) {
                $table->dropColumn('shift');
            }
        });
    }
};