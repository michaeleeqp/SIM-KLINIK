<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asuhan extends Model
{
    use HasFactory;

    protected $table = 'ashuhans';

    protected $fillable = [
        'patient_id',
        'user_id',
        'poli_tujuan',
        'tanggal_asuhan',
        'admission_date',
        'discharge_date',
        'keluhan_utama',
        'riwayat_penyakit',
        'riwayat_alergi',
        'diagnosa_medis',
        'tindakan_terapi',
        'resep',
        'resep_collected_at',
        'resep_total',
        'catatan_perawatan',
        'tekanan_darah',
        'nadi',
        'suhu',
        'respirasi',
        'status',
    ];

    protected $casts = [
        'tanggal_asuhan' => 'date',
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'suhu' => 'float',
        'resep_collected_at' => 'datetime',
        'resep_total' => 'float',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class, 'asuhan_id');
    }
}
