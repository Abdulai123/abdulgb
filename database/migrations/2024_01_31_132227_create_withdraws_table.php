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
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->onUpdate('cascade')->onDelete('cascade');
            $table->text('address');
            $table->decimal('amount', 12, 4);
            $table->text('txid')->nullable();
            $table->boolean('is_confirm')->default(0);
            $table->boolean('is_detected')->default(0);
            $table->boolean('is_failed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraws');
    }
};
