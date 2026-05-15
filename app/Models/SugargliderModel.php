<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SugargliderModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "sugargliders";
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode', 'nama', 'kelamin', 'tgl_lahir', 'warna', 'jenis', 'genetika', 'fenotype', 'indukan_betina', 'indukan_jantan', 'gambar', 'keterangan', 'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($sugarglider) {
            $sugarglider->collections()->delete();
        });
    }

    // Many to Many - Shelter Table
    public function shelters()
    {
        return $this->belongsToMany(ShelterModel::class, 'collections', 'shelter_id', 'sugarglider_id')->withTimestamps()->withTrashed();
    }

     // One to Many - Collections Table
    public function collections()
    {
        return $this->hasMany(CollectionModel::class, 'sugarglider_id')->withTrashed();
    }
}
