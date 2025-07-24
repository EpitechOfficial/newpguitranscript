<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewStudent extends Model
{
    use HasFactory;
    protected $table = 'new';
    protected $fillable = ['matric', 'password'];
}
