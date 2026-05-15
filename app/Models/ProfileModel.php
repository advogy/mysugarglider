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
        'user_id', 'alamat', 'kota', 'provinsi', 'telepon', 'bio', 'instagram', 'website'
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
