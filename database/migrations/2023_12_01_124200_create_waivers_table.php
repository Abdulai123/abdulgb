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
        Schema::create('waivers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
                $table->longText('reason');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->string('proof1', 256)->nullable();
                $table->string('proof2', 256)->nullable();
                $table->string('proof3', 256)->nullable();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waivers');
    }
};
