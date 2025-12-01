<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'category_sparepart', 'kategori_id', 'sparepart_id');
    }
}
