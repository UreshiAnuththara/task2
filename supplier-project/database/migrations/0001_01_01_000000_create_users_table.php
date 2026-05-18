<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // shift_type: null/none = no restriction, 'day' = 8-18, 'night' = 18-8, 'custom' = custom range
            if (! Schema::hasColumn('users', 'shift_type')) {
                $table->string('shift_type')->nullable()->after('shift');
            }
            // HH:MM format, e.g. "08:00"
            if (! Schema::hasColumn('users', 'shift_start')) {
                $table->string('shift_start', 5)->nullable()->after('shift_type');
            }
            if (! Schema::hasColumn('users', 'shift_end')) {
                $table->string('shift_end', 5)->nullable()->after('shift_start');
            }
        });

        // Migrate existing shift data → new columns
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

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = ['shift_type', 'shift_start', 'shift_end'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};