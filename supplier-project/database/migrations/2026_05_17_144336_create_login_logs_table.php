<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop first if exists (safe re-run)
        Schema::dropIfExists('login_logs');

        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');          // explicit column (no FK constraint issue)
            $table->string('shift')->nullable();             // day / night / null (admin)
            $table->string('role')->nullable();
            $table->timestamp('logged_in_at');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};