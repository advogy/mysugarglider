<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShelterTransferModel extends Model
{
    protected $table = 'shelter_transfers';

    protected $fillable = [
        'sugarglider_id', 'collection_id',
        'from_shelter_id', 'from_user_id',
        'to_shelter_id', 'to_user_id',
        'adoption_request_id',
    ];

    public function sugarglider()
    {
        return $this->belongsTo(SugargliderModel::class, 'sugarglider_id');
    }

    public function toShelter()
    {
        return $this->belongsTo(ShelterModel::class, 'to_shelter_id');
    }
}
