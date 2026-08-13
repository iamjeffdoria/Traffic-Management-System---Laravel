<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tricycle_mayors_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tricycle_id')->constrained()->cascadeOnDelete();
            $table->string('control_no')->unique();
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->string('business_name')->nullable();
            $table->string('motorized_operation');
            $table->string('or_no');
            $table->decimal('amount_paid', 10, 2);
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('issued_at');
            $table->string('mayor');
            $table->string('quarter');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tricycle_mayors_permits');
    }
};