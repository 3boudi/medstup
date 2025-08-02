<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing updated_at column to consultation_requests
        Schema::table('consultation_requests', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });

        // Add indexes for better performance
        Schema::table('consultation_requests', function (Blueprint $table) {
            $table->index(['doctor_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });

        Schema::table('chats', function (Blueprint $table) {
            $table->index('consultation_request_id');
            $table->index('is_closed');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index(['chat_id', 'sent_at']);
            $table->index(['sender_type', 'sender_id']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->index('status');
            $table->index('clinic_id');
        });

        Schema::table('doctor_specialization', function (Blueprint $table) {
            $table->index('doctor_id');
            $table->index('specialization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation_requests', function (Blueprint $table) {
            $table->dropColumn('updated_at');
            $table->dropIndex(['doctor_id', 'status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('chats', function (Blueprint $table) {
            $table->dropIndex(['consultation_request_id']);
            $table->dropIndex(['is_closed']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['chat_id', 'sent_at']);
            $table->dropIndex(['sender_type', 'sender_id']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['clinic_id']);
        });

        Schema::table('doctor_specialization', function (Blueprint $table) {
            $table->dropIndex(['doctor_id']);
            $table->dropIndex(['specialization_id']);
        });
    }
};