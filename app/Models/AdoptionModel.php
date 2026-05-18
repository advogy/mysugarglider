<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdoptionModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "adoptions";
    protected $primaryKey = 'id';
    protected $fillable = [
        'collection_id', 'user_id', 'harga', 'status', 'keterangan'
    ];

    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function collection()
    {
        return $this->belongsTo(CollectionModel::class, 'collection_id')->withTrashed();
    }

    public function requests()
    {
        return $this->hasMany(AdoptionRequestModel::class, 'adoption_id');
    }
}
