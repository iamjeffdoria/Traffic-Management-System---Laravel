<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanods', function (Blueprint $table) {
            $table->id();

            // Personal details
            $table->string('id_number')->unique();
            $table->string('full_name');
            $table->string('position');
            $table->string('barangay');
            $table->string('barangay_chairman');
            $table->string('home_address');
            $table->decimal('weight', 5, 2)->nullable();   // kg
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->decimal('height', 5, 2)->nullable();   // cm
            $table->string('blood_type', 5)->nullable();
            $table->string('mobile_no', 50)->nullable();

            // Emergency contact details
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_address');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_number', 50);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanods');
    }
};