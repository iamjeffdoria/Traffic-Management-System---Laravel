<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kasabutan_sellings', function (Blueprint $table) {
            $table->id();
            $table->string('owner');
            $table->string('barangay');
            $table->string('municipality');
            $table->string('body_number');
            $table->string('buyer');
            $table->string('buyer_address');
            $table->decimal('amount', 10, 2);
            $table->string('witness');
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kasabutan_sellings');
    }
};