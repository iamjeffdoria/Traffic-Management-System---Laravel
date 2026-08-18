<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('franchises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tricycle_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('valid_until');
            $table->string('plate_no');
            $table->string('denomination')->nullable();
            $table->string('status')->default('New');
            $table->string('authorized_no');
            $table->string('motor_no');
            $table->string('chassis_no');
            $table->text('authorized_route');
            $table->text('purpose')->nullable();
            $table->string('official_receipt_no');
            $table->decimal('amount_paid', 10, 2);
            $table->date('date');
            $table->string('municipal_treasurer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchises');
    }
};