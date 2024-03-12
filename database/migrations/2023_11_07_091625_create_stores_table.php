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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('store_name', 50);
            $table->longText('store_description')->nullable();
            $table->text('store_pgp')->nullable();
            $table->string('store_key');
            $table->integer('width_sales')->default(0);
            $table->integer('disputes_lost')->default(0);
            $table->integer('disputes_won')->default(0);
            $table->integer('products_count')->default(0);
            $table->enum('status', ['active', 'vacation', 'banned', 'escalated'])->default('active');
            $table->text('selling');
            $table->text('ship_from');
            $table->text('ship_to');
            $table->string('avatar')->nullable();
            $table->string('referral_link', 128)->nullable();
            $table->date('last_updated');
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('is_fe_enable')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};

