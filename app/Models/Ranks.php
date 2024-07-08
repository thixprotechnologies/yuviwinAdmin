<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranks extends Model
{
    use HasFactory;
    protected $table = "ranks";
    protected $fillable = [
        'username',
        'ranks',
        'amount'
    ];
}
