<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tricycles', function (Blueprint $table) {
            $table->string('driver')->nullable()->after('owner_number');
            $table->string('driver_number')->nullable()->after('driver');
        });
    }

    public function down(): void
    {
        Schema::table('tricycles', function (Blueprint $table) {
            $table->dropColumn(['driver', 'driver_number']);
        });
    }
};