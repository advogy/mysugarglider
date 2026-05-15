<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdoptionRequestModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "adoption_requests";
    protected $primaryKey = 'id';
    protected $fillable = [
        'adoption_id', 'harga', 'status', 'keterangan', 'user_id', 'shelter_id',
        'bukti_transfer', 'paid_at', 'confirmed_at',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'confirmed_at' => 'datetime',
    ];
}
