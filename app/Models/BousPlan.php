<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BousPlan extends Model
{
    use HasFactory;
    protected $table = "bonusplans";
    protected $fillable = [
        'bonus_plan_id',
        'type',
        'amount',
        'game_count',
        'rechare_value',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $lastUser = static::latest('id')->first();
                if ($lastUser) {
                    $lastNumber = (int)substr($lastUser->bonus_plan_id, strlen('BONPLAN_'));
                    $userCode = $lastNumber + 1;
                } else {
                    $userCode = 001;
                }
                $userId = 'BONPLAN_' .str_pad($userCode, 3, '0', STR_PAD_LEFT);
            $user->bonus_plan_id = $userId;
        });
    }
}
