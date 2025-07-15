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
       Schema::create('messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('chat_id')->constrained()->onDelete('cascade');
    $table->enum('sender_type', ['user', 'doctor']);
    $table->unsignedBigInteger('sender_id');
    
    $table->enum('type', ['text', 'image', 'file'])->default('text');   
    $table->text('message')->nullable();       
    $table->json('file_path')->nullable();      

    $table->timestamp('sent_at')->useCurrent();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};