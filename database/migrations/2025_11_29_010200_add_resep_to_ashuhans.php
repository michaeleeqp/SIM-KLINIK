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
            $table->text('resep')->nullable()->after('tindakan_terapi');
            $table->dateTime('resep_collected_at')->nullable()->after('resep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ashuhans', function (Blueprint $table) {
            $table->dropColumn(['resep', 'resep_collected_at']);
        });
    }
};
