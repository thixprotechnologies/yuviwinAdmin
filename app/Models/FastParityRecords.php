<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastParityRecords extends Model
{
    use HasFactory;
    protected $table = "betrec";
    public $timestamps = false;
    protected $fillable = [
        'period',
        'ans',
        'num',
        'clo',
        'res1'
    ];

}
