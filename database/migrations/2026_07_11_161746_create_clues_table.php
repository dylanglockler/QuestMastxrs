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
        Schema::create('clues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hunt_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order');
            $table->string('title')->nullable();
            $table->text('riddle_text');
            $table->text('location_note')->nullable();
            $table->timestamps();

            $table->unique(['hunt_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clues');
    }
};
