<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tricycles', function (Blueprint $table) {
            $table->string('owner_number')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('tricycles', function (Blueprint $table) {
            $table->dropColumn('owner_number');
        });
    }
};