<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseNew extends Model
{
    protected $table = 'course_new';

    public function course()
    {
        return $this->hasMany(CourseNew::class);
    }
}
