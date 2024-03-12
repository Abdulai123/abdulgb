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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('actor_id')->unsigned()->unsigned()->nullable();
            $table->bigInteger('notification_type_id')->unsigned();
            $table->bigInteger('option_id')->unsigned()->unsigned()->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->timestamps();

            // Foreign key constraints with cascade on update and restrict on delete
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade')->onDelete('cascade');
            $table->foreign('actor_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade')->onDelete('cascade');
            $table->foreign('notification_type_id')->references('id')->on('notification_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
