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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('sold')->default(0);
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->enum('product_type', ['digital', 'physical'])->default('physical');
            $table->string('ship_from')->default('Unknown');
            $table->enum('payment_type', ['Escrow', 'FE'])->default('Escrow');
            $table->string('ship_to')->default('World Wide');
            $table->foreignId('parent_category_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->text('return_policy')->nullable();
            $table->text('auto_delivery_content')->nullable();
            $table->integer('disputes_lost')->default(0);
            $table->integer('disputes_won')->default(0);
            $table->enum('category_promote', ['yes', 'no'])->default('no');
            $table->enum('search_promote', ['yes', 'no'])->default('no');
            $table->enum('status', ['Active', 'Pending', 'Rejected', 'Paused'])->default('Pending');
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_promoted')->default(0);
            $table->string('image_path1')->nullable();
            $table->string('image_path2')->nullable();
            $table->string('image_path3')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

