<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    public $timestamps = false;
    protected $fillable = [
        'balance',
        'bonus'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $lastUser = static::latest('id')->first();

            if ($lastUser) {
                $userCode = $lastUser->usercode + 1;
            } else {
                $userCode = 3465;
            }
            $user->usercode = $userCode;
        });
    }

    public function getTotalUserCountAttribute()
    {
        return User::where('refcode', $this->usercode)->count();
    }
}
