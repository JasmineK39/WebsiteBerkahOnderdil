<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_mobil_id',
        'name',
        'brand',
        'type',
        'grade',
        'stock',
        'price',
        'description',
        'image',
        'status'
    ];

<<<<<<< HEAD
=======
    

>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
    public function modelMobil()
    {
        return $this->belongsTo(ModelMobil::class, 'model_mobil_id');
    }

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'category_sparepart', 'sparepart_id', 'kategori_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function itemCarts()
    {
        return $this->hasMany(ItemCard::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
<<<<<<< HEAD
=======

    public function getImageUrlAttribute()
{
    if (!$this->image) {
        return null;
    }
    return asset('storage/' . $this->image);
}

>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
}
