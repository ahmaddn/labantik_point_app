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
        Schema::table('p_config_handlings', function (Blueprint $table) {
            $table->string('letter_type')->nullable()->after('handling_action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_config_handlings', function (Blueprint $table) {
            $table->dropColumn('letter_type');
        });
    }
};
