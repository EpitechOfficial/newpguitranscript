<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransDetailsFiles extends Model
{
    use HasFactory;

    protected $fillable = ['trans_details_id', 'file_path'];

    public function transDetails()
    {
        return $this->belongsTo(TransDetailsNew::class, 'trans_details_id');
    }
}
