<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShelterModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "shelters";
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama', 'kode', 'alamat', 'gmaps', 'status', 'user_id', 'gambar', 'keterangan'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($shelter) {
            $shelter->collections()->delete();
        });
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Many to Many - Sugar Glider Table
    public function sugargliders()
    {
        return $this->belongsToMany(SugargliderModel::class, 'collections', 'shelter_id', 'sugarglider_id')->withTimestamps()->withTrashed();
    }

    // One to Many - Collections Table
    public function collections()
    {
        return $this->hasMany(CollectionModel::class, 'shelter_id')->withTrashed();
    }
}
