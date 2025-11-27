<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UGD extends Model
{
    protected $table = 'ugd';

    protected $fillable = [
        'kunjungan_id',
        'keluhan_utama',
        'riwayat_penyakit',
        'riwayat_alergi',
        'diagnosa_medis',
        'tindakan_terapi',
        'catatan_perawatan',
        'sistole',         // Pastikan ini ada
        'diastole',        // Pastikan ini ada
        'nadi',
        'suhu',            // Pastikan ini ada
        'respirasi'
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }
}
