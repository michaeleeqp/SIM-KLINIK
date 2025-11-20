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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            //data dasar pasien
            $table->string('nama_pasien', 100);
            $table->string('no_rm', 6)->unique();
            $table->string('no_ktp', 16)->unique();

            //informasi pribadi pasien
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('status_perkawinan');
            $table->string('status_keluarga');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('golongan_darah');
            $table->text('alamat');
            $table->string('no_wa',13);
            $table->string('pekerjaan');

            // Wilayah administratif
            $table->unsignedBigInteger('provinsi_id');
            $table->unsignedBigInteger('kabupaten_id');
            $table->unsignedBigInteger('kecamatan_id');
            $table->unsignedBigInteger('desa_id');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
