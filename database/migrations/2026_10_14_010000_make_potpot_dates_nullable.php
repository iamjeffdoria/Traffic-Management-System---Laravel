<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('potpots', function (Blueprint $table) {
            $table->date('date_registered')->nullable()->change();
            $table->date('date_expired')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('potpots', function (Blueprint $table) {
            $table->date('date_registered')->nullable(false)->change();
            $table->date('date_expired')->nullable(false)->change();
        });
    }
};