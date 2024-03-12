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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('store_id')->unsigned();
            $table->integer('communication_rating'); // Rating for communication (1 to 5)
            $table->integer('product_rating'); // Rating for product quality (1 to 5)
            $table->integer('shipping_speed_rating'); // Rating for shipping speed (1 to 5)
            $table->integer('price_rating'); // Rating for price (1 to 5)
            $table->enum('feedback', ['positive', 'neutral', 'negative']); // Feedback type
            $table->integer('order_id')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        
            // Foreign key constraints with cascade on update and restrict on delete
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
