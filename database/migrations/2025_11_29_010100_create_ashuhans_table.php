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
        Schema::create('ashuhans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('poli_tujuan', 50); // ugd, umum, rawat_inap
            $table->date('tanggal_asuhan');
            
            // Keluhan dan Riwayat
            $table->text('keluhan_utama')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('riwayat_alergi')->nullable();
            
            // Diagnosa dan Tindakan
            $table->text('diagnosa_medis')->nullable();
            $table->text('tindakan_terapi')->nullable();
            $table->text('catatan_perawatan')->nullable();
            
            // Tanda-tanda Vital
            $table->string('tekanan_darah', 50)->nullable(); // 120/80
            $table->integer('nadi')->nullable(); // bpm
            $table->decimal('suhu', 4, 1)->nullable(); // °C
            $table->integer('respirasi')->nullable(); // x/menit
            
            // Status
            $table->enum('status', ['draft', 'final'])->default('draft');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ashuhans');
    }
};
