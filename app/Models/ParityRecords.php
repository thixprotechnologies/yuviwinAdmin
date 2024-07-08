<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParityRecords extends Model
{
    use HasFactory;
    protected $table = "emredbetrec";
    public $timestamps = false;
    protected $fillable = [
        'period',
        'ans',
        'num',
        'clo',
        'res1'
    ];
}
