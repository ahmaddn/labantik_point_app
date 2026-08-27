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
        Schema::create('p_point_reductions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('ref_student_id', 36)->index();
            $table->string('academic_year');
            $table->integer('points_reduced');
            $table->string('reason')->nullable();
            $table->char('created_by', 36)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_point_reductions');
    }
};
