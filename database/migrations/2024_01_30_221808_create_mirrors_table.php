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
        Schema::create('mirrors', function (Blueprint $table) {
            $table->id();
            $table->text('link')->nullable();
            $table->enum('type', ['user', 'store', 'junior', 'senior', 'admin']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mirrors');
    }
};
