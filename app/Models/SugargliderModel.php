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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public static function silsilah(int $id): ?object
    {
        return static::leftjoin('sugargliders as m',  'sugargliders.indukan_jantan', '=', 'm.id')
            ->leftjoin('sugargliders as f',  'sugargliders.indukan_betina', '=', 'f.id')
            ->leftjoin('sugargliders as mm', 'm.indukan_jantan',  '=', 'mm.id')
            ->leftjoin('sugargliders as mf', 'm.indukan_betina',  '=', 'mf.id')
            ->leftjoin('sugargliders as fm', 'f.indukan_jantan',  '=', 'fm.id')
            ->leftjoin('sugargliders as ff', 'f.indukan_betina',  '=', 'ff.id')
            ->leftjoin('sugargliders as mmm', 'mm.indukan_jantan', '=', 'mmm.id')
            ->leftjoin('sugargliders as mmf', 'mm.indukan_betina', '=', 'mmf.id')
            ->leftjoin('sugargliders as mfm', 'mf.indukan_jantan', '=', 'mfm.id')
            ->leftjoin('sugargliders as mff', 'mf.indukan_betina', '=', 'mff.id')
            ->leftjoin('sugargliders as fmm', 'fm.indukan_jantan', '=', 'fmm.id')
            ->leftjoin('sugargliders as fmf', 'fm.indukan_betina', '=', 'fmf.id')
            ->leftjoin('sugargliders as ffm', 'ff.indukan_jantan', '=', 'ffm.id')
            ->leftjoin('sugargliders as fff', 'ff.indukan_betina', '=', 'fff.id')
            ->leftjoin('sugargliders as mmmm', 'mmm.indukan_jantan', '=', 'mmmm.id')
            ->leftjoin('sugargliders as mmmf', 'mmm.indukan_betina', '=', 'mmmf.id')
            ->leftjoin('sugargliders as mmfm', 'mmf.indukan_jantan', '=', 'mmfm.id')
            ->leftjoin('sugargliders as mmff', 'mmf.indukan_betina', '=', 'mmff.id')
            ->leftjoin('sugargliders as mfmm', 'mfm.indukan_jantan', '=', 'mfmm.id')
            ->leftjoin('sugargliders as mfmf', 'mfm.indukan_betina', '=', 'mfmf.id')
            ->leftjoin('sugargliders as mffm', 'mff.indukan_jantan', '=', 'mffm.id')
            ->leftjoin('sugargliders as mfff', 'mff.indukan_betina', '=', 'mfff.id')
            ->leftjoin('sugargliders as fmmm', 'fmm.indukan_jantan', '=', 'fmmm.id')
            ->leftjoin('sugargliders as fmmf', 'fmm.indukan_betina', '=', 'fmmf.id')
            ->leftjoin('sugargliders as fmfm', 'fmf.indukan_jantan', '=', 'fmfm.id')
            ->leftjoin('sugargliders as fmff', 'fmf.indukan_betina', '=', 'fmff.id')
            ->leftjoin('sugargliders as ffmm', 'ffm.indukan_jantan', '=', 'ffmm.id')
            ->leftjoin('sugargliders as ffmf', 'ffm.indukan_betina', '=', 'ffmf.id')
            ->leftjoin('sugargliders as fffm', 'fff.indukan_jantan', '=', 'fffm.id')
            ->leftjoin('sugargliders as ffff', 'fff.indukan_betina', '=', 'ffff.id')
            ->select(
                'sugargliders.nama as nama', 'sugargliders.id as id', 'sugargliders.jenis as jenis',
                'm.nama as mNama',  'm.id as mId',  'm.jenis as mJenis',
                'f.nama as fNama',  'f.id as fId',  'f.jenis as fJenis',
                'mm.nama as mmNama','mm.id as mmId','mm.jenis as mmJenis',
                'mf.nama as mfNama','mf.id as mfId','mf.jenis as mfJenis',
                'fm.nama as fmNama','fm.id as fmId','fm.jenis as fmJenis',
                'ff.nama as ffNama','ff.id as ffId','ff.jenis as ffJenis',
                'mmm.nama as mmmNama', 'mmm.id as mmmId', 'mmm.jenis as mmmJenis',
                'mmf.nama as mmfNama', 'mmf.id as mmfId', 'mmf.jenis as mmfJenis',
                'mfm.nama as mfmNama', 'mfm.id as mfmId', 'mfm.jenis as mfmJenis',
                'mff.nama as mffNama', 'mff.id as mffId', 'mff.jenis as mffJenis',
                'fmm.nama as fmmNama', 'fmm.id as fmmId', 'fmm.jenis as fmmJenis',
                'fmf.nama as fmfNama', 'fmf.id as fmfId', 'fmf.jenis as fmfJenis',
                'ffm.nama as ffmNama', 'ffm.id as ffmId', 'ffm.jenis as ffmJenis',
                'fff.nama as fffNama', 'fff.id as fffId', 'fff.jenis as fffJenis',
                'mmmm.nama as mmmmNama','mmmm.id as mmmmId','mmmm.jenis as mmmmJenis',
                'mmmf.nama as mmmfNama','mmmf.id as mmmfId','mmmf.jenis as mmmfJenis',
                'mmfm.nama as mmfmNama','mmfm.id as mmfmId','mmfm.jenis as mmfmJenis',
                'mmff.nama as mmffNama','mmff.id as mmffId','mmff.jenis as mmffJenis',
                'mfmm.nama as mfmmNama','mfmm.id as mfmmId','mfmm.jenis as mfmmJenis',
                'mfmf.nama as mfmfNama','mfmf.id as mfmfId','mfmf.jenis as mfmfJenis',
                'mffm.nama as mffmNama','mffm.id as mffmId','mffm.jenis as mffmJenis',
                'mfff.nama as mfffNama','mfff.id as mfffId','mfff.jenis as mfffJenis',
                'fmmm.nama as fmmmNama','fmmm.id as fmmmId','fmmm.jenis as fmmmJenis',
                'fmmf.nama as fmmfNama','fmmf.id as fmmfId','fmmf.jenis as fmmfJenis',
                'fmfm.nama as fmfmNama','fmfm.id as fmfmId','fmfm.jenis as fmfmJenis',
                'fmff.nama as fmffNama','fmff.id as fmffId','fmff.jenis as fmffJenis',
                'ffmm.nama as ffmmNama','ffmm.id as ffmmId','ffmm.jenis as ffmmJenis',
                'ffmf.nama as ffmfNama','ffmf.id as ffmfId','ffmf.jenis as ffmfJenis',
                'fffm.nama as fffmNama','fffm.id as fffmId','fffm.jenis as fffmJenis',
                'ffff.nama as ffffNama','ffff.id as ffffId','ffff.jenis as ffffJenis',
            )
            ->where('sugargliders.id', $id)
            ->first();
    }
}
