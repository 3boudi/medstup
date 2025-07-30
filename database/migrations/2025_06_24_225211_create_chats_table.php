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
     Schema::create('chats', function (Blueprint $table) {
    $table->id();
    $table
        ->foreignId('consultation_request_id')
        ->unique()
        ->constrained('consultation_requests')
        ->onDelete('cascade');
    $table->boolean('is_closed')->default(false);
    $table->timestamp('closed_at')->nullable();
    $table->timestamps(); 
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
        
    }
};
