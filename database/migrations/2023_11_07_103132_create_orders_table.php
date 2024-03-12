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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->foreignId('store_id')->constrained('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('extra_option_id')->constrained('extra_options')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->text('shipping_address')->nullable();
            $table->text('store_notes')->nullable();
            $table->decimal('extra_amount', 10,2)->default(0.00);
            $table->decimal('cost_per_item', 10,2)->default(0.00);
            $table->decimal('discount', 10,2)->default(0.00);
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'dispute', 'sent', 'dispatched', 'cancelled', 'completed'])->default('pending');

            // Foreign key constraints with cascade on update and restrict on delete
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
