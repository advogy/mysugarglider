<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointConfig extends Model
{
    protected $table = 'point_configs';
    protected $fillable = ['key', 'value', 'keterangan'];
}
