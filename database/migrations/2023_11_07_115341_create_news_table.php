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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->longText('content')->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->bigInteger('author_id')->unsigned();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

            // Foreign key constraints with cascade on update and restrict on delete
            $table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
