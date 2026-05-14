<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('players', function (Blueprint $table) {
            $table->id(); // Field 1 [cite: 34]
            $table->string('ign'); // Field 2 [cite: 35]
            $table->string('email'); // Field 3 [cite: 36]
            $table->string('hero'); // Field 4 [cite: 36]
            $table->string('rank'); // Field 5 [cite: 36]
            $table->string('role'); 
            $table->integer('matches');
            $table->text('reason');
            $table->timestamps(); // Field 6 [cite: 37]
        });
    }
    public function down(): void { Schema::dropIfExists('players'); }
};