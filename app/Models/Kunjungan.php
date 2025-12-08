<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungans'; // tambahkan ini ✅

    protected $fillable = [
        'patient_id',
        'rujukan_dari',
        'keterangan_rujukan',
        'tanggal_kunjungan',
        'poli_tujuan',
        'jadwal_dokter',
        'kunjungan',
        'jenis_bayar',
        'no_asuransi',
        'pj_nama',
        'pj_no_ktp',
        'pj_alamat',
        'pj_no_wa',
        'catatan_kunjungan',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
