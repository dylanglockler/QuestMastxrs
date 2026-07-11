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
        Schema::create('hints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clue_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('order');
            $table->text('text');
            $table->timestamps();

            $table->unique(['clue_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hints');
    }
};
