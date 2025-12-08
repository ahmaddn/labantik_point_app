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
        Schema::table('p_viol_action_details', function (Blueprint $table) {
            $table->integer('violation_count')->default(0)->after('facing');
            $table->json('violations')->nullable()->after('violation_count'); // Menyimpan array pelanggaran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_viol_action_details', function (Blueprint $table) {
            $table->dropColumn(['violation_count', 'violations']);
        });
    }
};
