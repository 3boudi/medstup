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
        Schema::create('consultation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table
                ->foreignId('doctor_id')
                ->constrained('doctors')->onDelete('cascade');
            $table
                ->enum('status', ['pending', 'accepted', 'rejected'])
                ->default('pending');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_requests');
    }
};
