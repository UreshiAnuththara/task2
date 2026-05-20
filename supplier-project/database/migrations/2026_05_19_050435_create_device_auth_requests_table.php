<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the device_auth_requests table.
 *
 * Stores every "new device" login attempt that requires admin approval.
 * Once approved, a cookie token is written to the browser so that device
 * is remembered permanently.
 *
 * Columns:
 *  user_id         — who is trying to log in
 *  device_token    — unique fingerprint token generated in JS (stored in cookie)
 *  fingerprint     — JSON blob from JS fingerprinting (browser, OS, screen, etc.)
 *  ip_address      — client IP at time of request
 *  user_agent      — raw user-agent string
 *  status          — pending | approved | rejected
 *  requested_at    — when the request was first made
 *  responded_at    — when admin approved / rejected
 *  approved_by     — user_id of the admin who acted
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_auth_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // 64-char hex token — written to browser cookie on approval
            $table->string('device_token', 64)->unique();

            // JSON fingerprint data collected in the browser
            $table->json('fingerprint')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // pending | approved | rejected
            $table->string('status', 20)->default('pending');

            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('device_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_auth_requests');
    }
};