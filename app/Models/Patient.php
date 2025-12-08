<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    
    protected $table = 'patients'; // tambahkan ini ✅
    
    protected $fillable = [
        'nama_pasien',
        'no_rm',
        'no_ktp',
        'agama',
        'pendidikan',
        'status_perkawinan',
        'status_keluarga',
        'tanggal_lahir',
        'jenis_kelamin',
        'golongan_darah',
        'alamat',
        'no_wa',
        'pekerjaan',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',        
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
    
    public function kunjungans()
    {
        return $this->hasMany(\App\Models\Kunjungan::class, 'patient_id');
    }
}
