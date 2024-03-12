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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('public_name');
            $table->string('private_name');
            $table->integer('pin_code');
            $table->string('password');
            $table->string('store_key');
            $table->double('spent', 16, 2)->default(0.00);
            $table->string('login_passphrase');
            $table->integer('disputes_lost')->default(0);
            $table->integer('disputes_won')->default(0);
            $table->enum('twofa_enable', ['yes', 'no'])->default('no');
            $table->enum('role', ['user', 'store', 'junior', 'senior', 'admin'])->default('user');
            $table->enum('status', ['active', 'banned', 'vacation'])->default('active');
            $table->enum('store_status', ['active', 'in_active', 'pending', 'suspended', 'banned'])->default('in_active');
            $table->longText('pgp_key')->nullable();
            $table->boolean('show_key')->default(0);
            $table->string('private_url')->nullable();
            $table->integer('total_orders')->default(0);
            $table->enum('theme', ['white', 'dark'])->default('white');
            $table->string('referral_link')->nullable();
            $table->timestamp('last_seen')->nullable()->useCurrent();
            $table->string('avater')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

