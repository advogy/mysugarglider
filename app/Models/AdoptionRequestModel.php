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
        'bukti_transfer', 'paid_at', 'confirmed_at', 'platform_fee', 'disbursed_at',
        'nama_ekspedisi', 'resi_pengiriman', 'bukti_pengiriman',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'confirmed_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    public function adoption()
    {
        return $this->belongsTo(AdoptionModel::class, 'adoption_id');
    }

    public function applicant()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function shelter()
    {
        return $this->belongsTo(ShelterModel::class, 'shelter_id')->withTrashed();
    }
}
