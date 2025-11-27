<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_date',
        'total_amount',
        'status',
        'payment_method',
        'whatsapp_link',
        'location',
        'address'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaksi_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
