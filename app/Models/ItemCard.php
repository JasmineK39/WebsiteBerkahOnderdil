<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'keranjang_id',
        'sparepart_id',
        'quantity',
        'price'
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
