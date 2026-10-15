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
        Schema::create('kasabutan_lost_items', function (Blueprint $table) {
            $table->id();
            $table->string('driver');
            $table->string('body_number');
            $table->unsignedSmallInteger('driver_age');
            $table->string('driver_address');
            $table->decimal('amount', 10, 2);
            $table->string('passenger');
            $table->unsignedSmallInteger('passenger_age');
            $table->string('passenger_address');
            $table->date('date');
            $table->time('time');
            $table->string('issued_at');
            $table->string('witness_1');
            $table->string('witness_2');
            $table->string('it_section');
            $table->string('security_officer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasabutan_lost_items');
    }
};
