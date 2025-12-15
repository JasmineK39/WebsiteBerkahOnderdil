<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\Review;
use App\Models\RequestSparepart;
use App\Notifications\CustomResetPassword;

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

    /**
     * Override the default password reset notification to send a frontend link.
     * Uses FRONTEND_URL env if set, otherwise falls back to app.url.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $frontend = env('FRONTEND_URL', config('app.url'));
        $frontend = rtrim($frontend, '/');
        // Use URL fragment so the token is not sent to the server in requests or logs.
        // Use a distinct key `reset_token` to avoid colliding with OAuth `token` fragments.
        // Example: https://frontend.example/auth/reset-password#reset_token=...&email=...
        // Router uses `/auth/reset-password`, so build URL to that path.
        $url = $frontend . '/auth/reset-password#reset_token=' . $token . '&email=' . urlencode($this->email);
        $this->notify(new CustomResetPassword($url));
    }
}