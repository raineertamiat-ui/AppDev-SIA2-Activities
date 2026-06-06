<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id'); 
            $table->date('appointment_date'); 
            $table->time('appointment_time'); 
            $table->string('service_type'); 
            $table->string('status')->default('Pending'); // 'Pending', 'Approved', 'Cancelled', 'Completed'
            $table->unsignedBigInteger('pet_id'); 
            $table->unsignedBigInteger('vet_id')->nullable(); 
            $table->timestamps();

            $table->foreign('pet_id')->references('pet_id')->on('pets')->onDelete('cascade');
            $table->foreign('vet_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('appointments');
    }
};