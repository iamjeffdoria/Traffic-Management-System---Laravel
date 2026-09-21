<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->date('license_issued_date')->nullable()->after('municipal_treasurer');
            $table->string('license_issued_at')->nullable()->after('license_issued_date');
        });
    }

    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn(['license_issued_date', 'license_issued_at']);
        });
    }
};