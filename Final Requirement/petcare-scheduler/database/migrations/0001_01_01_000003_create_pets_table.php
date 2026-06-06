<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pets', function (Blueprint $table) {
            $table->id('pet_id'); 
            $table->string('pet_name'); 
            $table->string('type'); 
            $table->string('breed')->nullable(); 
            $table->integer('age')->nullable(); 
            $table->unsignedBigInteger('owner_id'); 
            $table->timestamps();

            $table->foreign('owner_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pets');
    }
};