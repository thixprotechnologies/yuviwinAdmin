<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParitySet extends Model
{
    use HasFactory;
    protected $table = "emredperiod";
    public $timestamps = false;
}
