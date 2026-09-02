<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tricycles', function (Blueprint $table) {
            $table->id();
            $table->string('body_number');
            $table->string('plate_no')->unique();
            $table->string('name');
            $table->string('address');
            $table->string('make_kind');
            $table->string('status')->default('active'); // e.g. active, renewed, expired
            $table->string('engine_motor_no')->nullable();
            $table->string('chassis_no')->nullable();
            $table->date('date_registered');
            $table->date('date_expired');
            $table->string('toda')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tricycles');
    }
};