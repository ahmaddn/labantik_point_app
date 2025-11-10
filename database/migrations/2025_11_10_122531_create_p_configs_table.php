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
        Schema::create('p_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('academic_year')->unique();
            $table->boolean('is_active')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->char('created_by')->nullable();
            $table->char('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on('core_users');
            $table->foreign('updated_by')->references('id')->on('core_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_configs');
    }
};
