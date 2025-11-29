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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Video Info
            $table->string('title');
            $table->string('slug');
            $table->string('thumbnail');
            // $table->string('filename')->nullable();
            $table->string('external_link')->nullable();
            $table->enum('status', ['published', 'draft'])->default('published');

            // Tracking
            $table->integer('views_count')->default(fake()->numberBetween(50000, 2000000));
            $table->integer('views_count_real')->default(0);

            // Relation
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
