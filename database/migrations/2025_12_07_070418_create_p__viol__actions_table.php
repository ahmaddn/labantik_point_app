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
        Schema::create('p_viol_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('p_student_academic_year_id');
            $table->uuid('handling_id');
            $table->uuid('handled_by');
            $table->string('activity')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p__viol__actions');
    }
};
