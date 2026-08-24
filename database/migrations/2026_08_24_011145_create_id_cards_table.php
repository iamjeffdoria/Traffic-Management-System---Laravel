<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('id_cards', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('id_number')->unique();
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('address');
            $table->decimal('height', 5, 2);
            $table->decimal('weight', 5, 2);
            $table->string('or_number');
            $table->date('date_issued');
            $table->date('expiry_date');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('id_cards');
    }
};