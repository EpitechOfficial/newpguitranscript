<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;
    protected $table = 'courier';
    public $timestamps = false;
    protected $fillable = [
        'appno',
        'surname',
        'othernames',
        'email',
        'phone',
        'courier_name',
        'destination',
        'address',
        'address2',
        'courier_type',
        'perm_address',
        'date',
    ];
}
