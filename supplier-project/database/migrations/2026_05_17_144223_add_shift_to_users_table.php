<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Ensures the users table has the three shift columns:
 *   shift_type  (string, nullable) — 'day' | 'night' | null
 *   shift_start (string 5, nullable) — 'HH:MM'
 *   shift_end   (string 5, nullable) — 'HH:MM'
 *
 * Also migrates any existing legacy 'shift' column data to the new columns.
 *
 * Safe to run multiple times (uses hasColumn guards).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'shift_type')) {
                $table->string('shift_type')->nullable()->after('shift');
            }
            if (! Schema::hasColumn('users', 'shift_start')) {
                $table->string('shift_start', 5)->nullable()->after('shift_type');
            }
            if (! Schema::hasColumn('users', 'shift_end')) {
                $table->string('shift_end', 5)->nullable()->after('shift_start');
            }
        });

        // Migrate existing legacy 'shift' column data → new columns
        if (Schema::hasColumn('users', 'shift')) {
            DB::table('users')->whereNotNull('shift')->each(function ($user) {
                if ($user->shift === 'day') {
                    DB::table('users')->where('id', $user->id)->update([
                        'shift_type'  => 'day',
                        'shift_start' => '08:00',
                        'shift_end'   => '18:00',
                    ]);
                } elseif ($user->shift === 'night') {
                    DB::table('users')->where('id', $user->id)->update([
                        'shift_type'  => 'night',
                        'shift_start' => '18:00',
                        'shift_end'   => '08:00',
                    ]);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['shift_type', 'shift_start', 'shift_end'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};