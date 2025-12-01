<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestSparepart extends Model
{
    use HasFactory;

    protected $table = 'requests'; // karena nama tabel-nya bukan "request_spareparts"

    protected $fillable = [
        'user_id',
        'brand_req',
        'model_req',
        'year_req',
        'sparepart_req',
        'note',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
