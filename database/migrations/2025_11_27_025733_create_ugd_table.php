<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ugd', function (Blueprint $table) {
            $table->id();
            // Pastikan nama tabel referensinya benar (kunjungans)
            $table->foreignId('kunjungan_id')->constrained('kunjungans')->onDelete('cascade');

            // Asuhan Medis
            $table->string('keluhan_utama');
            $table->string('riwayat_penyakit')->nullable(); // nullable agar tidak error jika kosong
            $table->string('riwayat_alergi')->nullable();
            $table->string('diagnosa_medis');
            $table->string('tindakan_terapi'); // Tambahkan ini (sebelumnya tidak ada)
            $table->text('catatan_perawatan')->nullable();

            // Tanda Vital (Sesuaikan dengan nama name="" di Form Blade)
            $table->string('sistole', 10)->nullable();  // Ubah dari tekanan_darah menjadi pisah
            $table->string('diastole', 10)->nullable();
            $table->string('nadi', 10)->nullable();
            $table->string('suhu', 10)->nullable();     // Ubah dari suhu_tubuh ke suhu
            $table->string('respirasi', 10)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ugd');
    }
};
