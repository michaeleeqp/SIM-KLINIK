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
        Schema::table('ashuhans', function (Blueprint $table) {
            $table->decimal('resep_total', 12, 2)->nullable()->after('resep_collected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ashuhans', function (Blueprint $table) {
            $table->dropColumn('resep_total');
        });
    }
};
