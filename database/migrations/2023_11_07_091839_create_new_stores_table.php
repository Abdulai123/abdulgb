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
        Schema::create('new_stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_name', 255);
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->text('selling')->nullable();
            $table->string('ship_to', 255)->default('Worldwide');
            $table->string('ship_from', 255)->default('Unknown');
            $table->text('store_description');
            $table->text('sell_on')->nullable();
            $table->string('proof1', 255);
            $table->string('proof2', 255)->nullable();
            $table->string('proof3', 255)->nullable();
            $table->enum('store_type', ['paid', 'waiver'])->default('waiver');
            $table->text('avater');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_stores');
    }
};
