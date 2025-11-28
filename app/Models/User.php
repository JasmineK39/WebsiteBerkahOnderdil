<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'otp',
        'otp_expires_at',
        'resend_count',
        'last_resend_at'
    ];
    protected $casts = [
    'otp_expires_at' => 'datetime',
    'last_resend_at' => 'datetime',
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
