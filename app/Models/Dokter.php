<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    
    protected $table = 'dokter'; 
    
    protected $fillable = ['nama_dokter', 'spesialisasi', 'jadwal_praktek',];

    public function kunjungans()
    {
        return $this->hasMany(\App\Models\Kunjungan::class, 'patient_id');
    }
}