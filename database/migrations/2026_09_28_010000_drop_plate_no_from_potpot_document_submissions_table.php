<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('potpot_document_submissions', function (Blueprint $table) {
            $table->dropColumn('plate_no');
        });
    }

    public function down(): void
    {
        Schema::table('potpot_document_submissions', function (Blueprint $table) {
            $table->string('plate_no')->nullable()->after('body_number');
        });
    }
};