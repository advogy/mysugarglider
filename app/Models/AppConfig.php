<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    protected $table = 'app_configs';
    protected $fillable = ['key', 'label', 'value', 'keterangan', 'group', 'type'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $row = static::where('key', $key)->first();
        return ($row && $row->value !== null && $row->value !== '') ? $row->value : $default;
    }

    public static function byGroup(string $group)
    {
        return static::where('group', $group)->orderBy('id')->get();
    }
}
