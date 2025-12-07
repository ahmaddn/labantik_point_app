<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('p_viol_action_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('p_viol_action_id');
            $table->string('parent_name')->nullable();
            $table->string('student_name')->nullable();
            $table->date('prey')->nullable();
            $table->date('action_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('time')->nullable();
            $table->string('room')->nullable();
            $table->string('facing')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_viol_action_details');
    }
};
