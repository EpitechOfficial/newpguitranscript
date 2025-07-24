<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transinvoice extends Model
{
    use HasFactory;
    protected $table = 'transinvoice';
    public $timestamps = false;
    protected $fillable = [
        'appno','invoiceno','purpose','mth','dy','yr','amount_charge','amount_paid','cheque'
    ];
}
