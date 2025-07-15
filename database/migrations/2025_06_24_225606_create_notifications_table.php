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
        Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->string('notifiable_type'); // 'user', 'doctor', 'admin'
    $table->unsignedBigInteger('notifiable_id');
    $table->string('type');
    $table->json('data');
    $table->timestamp('read_at')->nullable();
    $table->timestamp('created_at')->useCurrent();

    // optional indexing for polymorphic relation
    $table->index(['notifiable_type', 'notifiable_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
