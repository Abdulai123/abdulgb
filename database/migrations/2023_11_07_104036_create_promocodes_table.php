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
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->string('code');
            $table->integer('discount');
            $table->enum('type', ['fixed', 'percentage']);
            $table->timestamp('expiration_date')->nullable();
            $table->integer('usage_limit')->default(null);
            $table->integer('times_used')->default(0);
            $table->enum('status', ['active','paused','expired']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promocodes');
    }
};
