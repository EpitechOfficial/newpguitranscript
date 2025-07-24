<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZmainApp extends Model
{
    use HasFactory;
    protected $table = 'zmain_app';
    public $timestamps = false;
    protected $fillable = [
        'user_id','faculty','department','degree','field_of_interest',
    ];
}
