<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tricycle_document_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('driver_name');
            $table->string('contact_number');
            $table->string('body_number')->nullable();
            $table->string('plate_no')->nullable();
            $table->string('endorsement_letter_path');
            $table->string('toda_certificate_path');
            $table->string('police_clearance_path');
            $table->string('or_cr_path');
            $table->string('drivers_license_path');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tricycle_document_submissions');
    }
};