BousPlan
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bonusPlansHistroy extends Model
{
    use HasFactory;
    protected $table = "bonusPlansHistroy";
    protected $fillable = [
        'bonus_plan_id',
        'type',
        'amount',
        'game_count',
        'rechare_value',
        'username',
        'is_claimed',
    ];

}
