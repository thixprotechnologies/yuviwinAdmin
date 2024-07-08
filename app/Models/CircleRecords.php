<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircleRecords extends Model
{
    use HasFactory;
    protected $table = "vipbetrec";
    public $timestamps = false;
    protected $fillable = [
        'period',
        'ans',
        'num',
        'clo'
    ];
}
