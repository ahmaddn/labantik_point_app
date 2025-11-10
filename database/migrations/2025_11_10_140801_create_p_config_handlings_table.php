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
        Schema::create('p_config_handlings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('p_config_id', 36);
            $table->integer('handling_point')->default(0);
            $table->string('handling_action')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p__config__handlings');
    }
};
