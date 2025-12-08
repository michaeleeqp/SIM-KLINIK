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
            $table->dateTime('admission_date')->nullable()->after('tanggal_asuhan');
            $table->dateTime('discharge_date')->nullable()->after('admission_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ashuhans', function (Blueprint $table) {
            $table->dropColumn(['admission_date', 'discharge_date']);
        });
    }
};
