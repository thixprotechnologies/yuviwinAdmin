<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JetRecords extends Model
{
    use HasFactory;
    protected $table = "crashgamerecord";
    public $timestamps = false;

}
