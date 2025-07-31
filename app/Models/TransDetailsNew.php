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
        'feildofinterest',
        'email',
        'status',
        'ecopy_email',
        'ecopy_address',
    ];

    public $timestamps = false;

    public function transInvoice()
{
    return $this->hasOne(TransInvoice::class, 'invoiceno', 'email');
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

  

    public function courier()
    {
        return $this->hasOne(Courier::class, 'invoiceno', 'email');
    }


}
