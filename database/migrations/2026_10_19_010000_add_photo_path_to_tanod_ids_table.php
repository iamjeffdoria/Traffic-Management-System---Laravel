<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tanod_ids', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('date_of_birth');
        });
    }

    public function down(): void
    {
        Schema::table('tanod_ids', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};