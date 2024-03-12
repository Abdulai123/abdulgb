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
        Schema::create('user_promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('promocode_id')->constrained('promocodes')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('discount');
            $table->enum('cart_state', ['not_checked_out', 'checked_out'])->default('not_checked_out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_promos');
    }
};
