<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id'); 
            
            // --- BAGIAN INI DIUBAH ---
            // Mengganti string 'dokter' menjadi relasi 'dokter_id'
            // 'constrained' mengarah ke nama tabel 'dokter'
            $table->foreignId('dokter_id')
                  ->constrained('dokter') 
                  ->onDelete('restrict'); 
            // -------------------------

            $table->string('rujukan_dari', 100);
            $table->string('keterangan_rujukan', 255);
            $table->date('tanggal_kunjungan');
            $table->string('poli_tujuan', 100);
            
            // Hapus baris ini: $table->string('dokter', 100); 
            // karena sudah diganti dokter_id di atas
            
            $table->string('kunjungan', 50);
            $table->string('jenis_bayar', 50);
            $table->string('no_asuransi', 50)->nullable();

            // Penanggungjawab
            $table->string('pj_nama')->nullable();
            $table->string('pj_no_ktp', 16)->nullable();
            $table->text('pj_alamat')->nullable();
            $table->string('pj_no_wa', 13)->nullable();
            $table->text('catatan_kunjungan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};