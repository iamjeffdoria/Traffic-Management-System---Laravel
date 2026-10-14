<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('potpots', function (Blueprint $table) {
            $table->id();
            $table->string('body_number')->unique();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('or_number')->nullable();
            $table->string('status')->default('active'); // active, renewed, expired
            $table->date('date_registered');
            $table->date('date_expired');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('potpots');
    }
};