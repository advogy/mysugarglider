<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'total_points', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
    ];

    public function profile()
    {
        return $this->hasOne(ProfileModel::class, 'user_id');
    }

    public function shelters()
    {
        return $this->hasMany(ShelterModel::class, 'user_id');
    }

    public function pointLogs()
    {
        return $this->hasMany(PointLog::class);
    }

    public function redemptions()
    {
        return $this->hasMany(Redemption::class);
    }

    public function availablePoints(): int
    {
        return (int) $this->pointLogs()->active()->sum('points');
    }

    public function level(): array
    {
        $pts = $this->total_points;

        return match(true) {
            $pts >= 5000 => ['label' => 'Master',   'min' => 5000, 'next' => null,  'color' => 'text-amber-500'],
            $pts >= 2000 => ['label' => 'Breeder',  'min' => 2000, 'next' => 5000,  'color' => 'text-yellow-500'],
            $pts >= 700  => ['label' => 'Peternak', 'min' => 700,  'next' => 2000,  'color' => 'text-sage-dark'],
            $pts >= 200  => ['label' => 'Anggota',  'min' => 200,  'next' => 700,   'color' => 'text-blue-500'],
            default      => ['label' => 'Pemula',   'min' => 0,    'next' => 200,   'color' => 'text-bark-muted'],
        };
    }
}
