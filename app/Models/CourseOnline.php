<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOnline extends Model
{
    use HasFactory;
    protected $table = 'course_online';
        protected $fillable = [
        'course',
        'title',
        'unit',
        'status',
        
    ];

    public function results()
    {
        return $this->hasMany(ResultOld::class, 'code', 'course');
    }
}
