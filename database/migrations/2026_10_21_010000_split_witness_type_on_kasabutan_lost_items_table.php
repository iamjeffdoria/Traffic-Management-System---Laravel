<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kasabutan_lost_items', function (Blueprint $table) {
            $table->string('witness_1_type')->default('Traffic Enforcer')->after('witness_2');
            $table->string('witness_2_type')->default('Traffic Enforcer')->after('witness_1_type');
        });

        // Existing records keep whatever single type they had, for both witnesses.
        DB::table('kasabutan_lost_items')->update([
            'witness_1_type' => DB::raw('witness_type'),
            'witness_2_type' => DB::raw('witness_type'),
        ]);

        Schema::table('kasabutan_lost_items', function (Blueprint $table) {
            $table->dropColumn('witness_type');
        });
    }

    public function down(): void
    {
        Schema::table('kasabutan_lost_items', function (Blueprint $table) {
            $table->string('witness_type')->default('Traffic Enforcer')->after('witness_2');
        });

        DB::table('kasabutan_lost_items')->update([
            'witness_type' => DB::raw('witness_1_type'),
        ]);

        Schema::table('kasabutan_lost_items', function (Blueprint $table) {
            $table->dropColumn(['witness_1_type', 'witness_2_type']);
        });
    }
};