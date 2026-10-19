<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kasabutan_sellings', function (Blueprint $table) {
            // Nullable so existing records don't break.
            $table->string('secretariat')->nullable()->after('witness');
        });
    }

    public function down(): void
    {
        Schema::table('kasabutan_sellings', function (Blueprint $table) {
            $table->dropColumn('secretariat');
        });
    }
};