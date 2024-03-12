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
        Schema::create('api_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('fiat')->default('USD');
            $table->string('rate')->default('1.00');
            $table->string('BTC')->default('0.0');
            $table->string('XMR')->default('0.00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_currencies');
    }
};
