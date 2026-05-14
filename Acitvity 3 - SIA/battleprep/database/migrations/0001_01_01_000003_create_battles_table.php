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
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->string('hero_name'); // Name of the ML Hero
            $table->string('role');      // Tank, Mage, etc.
            $table->string('strategy');  // Strategy used in-game
            $table->string('result');    // Win or Loss
            $table->text('notes');      // Post-match analysis
            $table->timestamps();        // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battles');
    }
};