<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $table = 'prescription_items';

    protected $fillable = [
        'asuhan_id',
        'medicine_id',
        'name',
        'unit',
        'price',
        'qty',
        'note',
    ];

    public function asuhan()
    {
        return $this->belongsTo(Asuhan::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
