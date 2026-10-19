<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanod_ids', function (Blueprint $table) {
            $table->id();

            $table->string('id_number')->unique();
            $table->string('full_name');
            $table->string('address');
            $table->date('date_of_birth');

            // Emergency contact person
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_address');
            $table->string('emergency_contact_number', 50);
            $table->string('emergency_contact_relationship');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanod_ids');
    }
};