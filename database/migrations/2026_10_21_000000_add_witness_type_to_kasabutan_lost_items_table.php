<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kasabutan_lost_items', function (Blueprint $table) {
            // Existing records keep "Traffic Enforcer" so old printouts don't change.
            $table->string('witness_type')->default('Traffic Enforcer')->after('witness_2');
        });
    }

    public function down(): void
    {
        Schema::table('kasabutan_lost_items', function (Blueprint $table) {
            $table->dropColumn('witness_type');
        });
    }
};