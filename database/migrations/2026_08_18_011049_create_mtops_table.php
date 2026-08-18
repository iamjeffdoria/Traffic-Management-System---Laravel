<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mtops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tricycle_id')->constrained()->cascadeOnDelete();
            $table->string('case_no')->unique();
            $table->unsignedInteger('no_of_units')->default(1);
            $table->string('route_operation');
            $table->date('date');
            $table->string('municipal_treasurer');
            $table->string('officer_in_charge');
            $table->string('mayor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mtops');
    }
};