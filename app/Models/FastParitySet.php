<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastParitySet extends Model
{
    use HasFactory;
    protected $table = "period";
    public $timestamps = false;
}
