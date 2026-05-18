<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "profiles";
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'kode_profil', 'alamat', 'kota', 'provinsi', 'telepon', 'bio', 'instagram', 'website',
        'bank_name', 'bank_account_number', 'bank_account_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profile_shelter()
    {
        return $this->hasMany(ShelterModel::class, 'profile_id');
    }
}
