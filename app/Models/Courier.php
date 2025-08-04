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
        'trans_details_id', // Add this field to link to TransDetailsNew
        'transcript_purpose', // Add this field to store specific transcript type for this copy
        'number_of_copies', // Add this field to store number of copies for this delivery
    ];

    // Relationship to TransDetailsNew
    public function transDetails()
    {
        return $this->belongsTo(TransDetailsNew::class, 'trans_details_id', 'id');
    }
}
