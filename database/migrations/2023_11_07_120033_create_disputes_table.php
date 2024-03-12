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
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('escrow_id')->unsigned()->nullable();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('mediator_id')->unsigned()->nullable();
            $table->bigInteger('conversation_id')->unsigned();
            $table->integer('user_partial_percent')->nullable();
            $table->integer('store_partial_percent')->nullable();
            $table->boolean('mediator_request')->default(0);
            $table->enum('status', ['open', 'Full Refund', 'Partial Refund', 'closed'])->default('open');
            $table->enum('winner', ['none', 'store', 'user', 'both'])->default('none');
            $table->boolean('user_refund_accept')->default(0);
            $table->boolean('store_refund_accept')->default(0);
            $table->boolean('user_refund_reject')->default(0);
            $table->boolean('store_refund_reject')->default(0);
            $table->enum('refund_initiated', ['User', 'Store', 'staff', 'none'])->default('none');
            $table->timestamps();

            // Foreign key constraints with cascade on update and restrict on delete
            $table->foreign('escrow_id')->references('id')->on('escrows')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('mediator_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
