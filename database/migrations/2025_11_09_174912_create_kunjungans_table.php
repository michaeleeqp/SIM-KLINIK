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
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id'); // relasi ke tabel pasien

            //rujukan dan kunjungan
            $table->string('rujukan_dari', 100);
            $table->string('keterangan_rujukan', 255);
            $table->date('tanggal_kunjungan');
            $table->string('poli_tujuan', 100);
            $table->string('jadwal_dokter', 100);
            $table->string('kunjungan', 50);
            $table->string('jenis_bayar', 50);
            $table->string('no_asuransi', 50)->nullable();

            //penanggugjawab
            $table->string('pj_nama')->nullable();
            $table->string('pj_no_ktp',16)->nullable();
            $table->text('pj_alamat')->nullable();
            $table->string('pj_no_wa',13)->nullable();
            $table->text('catatan_kunjungan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
