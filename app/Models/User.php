<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function keranjang()
    {
        return $this->hasOne(Keranjang::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function requests()
    {
        return $this->hasMany(RequestSparepart::class, 'user_id');
    }
}
