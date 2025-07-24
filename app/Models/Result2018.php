<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result2018 extends Model
{
    use HasFactory;
    protected $table = 'results';

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'matric', 'matric');
    }

    public function course()
    {
        return $this->belongsTo(CourseNew::class, 'c_id', 'cgpa_id'); // Assuming `id` is the primary key in course_new
    }
}
