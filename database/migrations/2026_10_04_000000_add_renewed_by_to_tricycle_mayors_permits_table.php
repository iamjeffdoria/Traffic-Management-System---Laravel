<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tricycle_mayors_permits', function (Blueprint $table) {
            // enum('active','expired') can't hold "renewed", so loosen it to a string.
            $table->string('status')->default('active')->change();
            $table->string('renewed_by')->nullable()->after('mayor');
        });
    }

    public function down(): void
    {
        Schema::table('tricycle_mayors_permits', function (Blueprint $table) {
            $table->dropColumn('renewed_by');
        });
    }
};