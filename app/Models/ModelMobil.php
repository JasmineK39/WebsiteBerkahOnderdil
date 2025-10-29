<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelMobil extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'year',
        'description',
        'image'
    ];

    public function spareparts()
    {
        return $this->hasMany(Sparepart::class);
    }
}
