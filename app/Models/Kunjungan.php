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
        'dokter_id',
        'rujukan_dari',
        'keterangan_rujukan',
        'tanggal_kunjungan',
        'poli_tujuan',        
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
    public function dokter()
    {
        // belongsTo(ModelTujuannya, 'nama_kolom_fk_di_tabel_ini')
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
}
