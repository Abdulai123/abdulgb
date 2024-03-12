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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('conversation_id')->constrained('conversations')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', ['pending', 'open', 'closed', 'escalated', 'de-escalated'])->default('pending');
            $table->foreignId('staff_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};
