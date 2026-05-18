<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();           // e.g. admin, Production, HR
            $table->string('description')->nullable();  // e.g. "Manages production floor"
            $table->boolean('is_system')->default(false); // true = cannot be deleted (admin)
            $table->timestamps();
        });

        // Seed default roles
        DB::table('user_roles')->insert([
            ['name' => 'admin',       'description' => 'Full system access. Cannot be restricted by shift.', 'is_system' => true,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Production',  'description' => 'Production floor staff.',                            'is_system' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HR',          'description' => 'Human Resources department.',                        'is_system' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Accounting',  'description' => 'Finance and accounting team.',                       'is_system' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Logistics',   'description' => 'Logistics and supply chain team.',                   'is_system' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sales',       'description' => 'Sales and business development.',                    'is_system' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};