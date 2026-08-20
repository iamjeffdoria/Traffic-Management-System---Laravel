<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn(['name', 'plate_no', 'motor_no', 'chassis_no']);
        });
    }

    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('plate_no')->nullable();
            $table->string('motor_no')->nullable();
            $table->string('chassis_no')->nullable();
        });
    }
};