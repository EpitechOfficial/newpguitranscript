<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransDetailsNew extends Model
{
    use HasFactory;
    protected $table = 'trans_details_new';

    protected $fillable = [
        'matric',
        'Surname',
        'Othernames',
        'maiden',
        'sex',
        'tittle',
        'degree',
        'sessionadmin',
        'sessiongrad',
        'faculty',
        'department',
        'Telephone',
        'date_requested',
        'award',
        'dateAward',
        'programme',
        'thesis_title',
        'feildofinterest',
        'email',
        'invoiceno',
        'status',
        'ecopy_email',
        'ecopy_address',
    ];

    public $timestamps = false;

    public function transInvoice()
{
    return $this->hasOne(TransInvoice::class, 'invoiceno', 'invoiceno');
}

public function course()
{
    return $this->hasMany(CourseNew::class, 'id');
}

public function file()
{
    return $this->hasOne(TransDetailsFiles::class, 'trans_details_id');
}

public function transDetailsFiles()
{
    return $this->hasMany(TransDetailsFiles::class, 'trans_details_id', 'id');
}

  

    // Updated relationship - one TransDetailsNew can have many Courier records
    public function couriers()
    {
        return $this->hasMany(Courier::class, 'trans_details_id', 'id');
    }

    // Keep the old relationship for backward compatibility
    public function courier()
    {
        return $this->hasOne(Courier::class, 'invoiceno', 'invoiceno');
    }

    // Helper method to get all courier destinations for this transcript
    public function getCourierDestinations()
    {
        return $this->couriers()->select('destination', 'address', 'address2')->get();
    }

    // Helper method to get the primary courier record (first one)
    public function getPrimaryCourier()
    {
        return $this->couriers()->first();
    }

    // Helper method to get all courier records with transcript information
    public function getCouriersWithTranscriptInfo()
    {
        return $this->couriers()->select(
            'courier_name',
            'destination', 
            'address', 
            'address2',
            'email',
            'phone',
            'transcript_purpose',
            'number_of_copies'
        )->get();
    }


}
