<?php

use Database\Seeders\CategoriesSeeder;
use Database\Seeders\ViolationsSeeder;
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
        Schema::create('p_recaps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('ref_student_id', 36);
            $table->char('p_violation_id', 36);
            $table->enum('status', ['pending', 'verified', 'not_verified'])->default('pending');
            $table->timestamps();
            $table->char('verified_by')->nullable();
            $table->char('created_by')->nullable();
            $table->char('updated_by')->nullable();
            $table->foreign('ref_student_id')
                ->references('id')
                ->on('ref_students')
                ->onDelete('cascade');
            $table->foreign('p_violation_id')->references('id')->on('p_violations');
            $table->foreign('verified_by')->references('id')->on('core_users');
            $table->foreign('created_by')->references('id')->on('core_users');
            $table->foreign('updated_by')->references('id')->on('core_users');
            $seederCat = new CategoriesSeeder();
            $seederCat->run();
            $seeder = new ViolationsSeeder();
            $seeder->run();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_recaps');
    }
};
