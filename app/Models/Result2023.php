<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result2023 extends Model
{
    use HasFactory;
    protected $table = 'testscore';
    public function getAttribute($key)
    {
        // If the attribute being accessed is 'status', return null
        if ($key === 'status' ) {
            return null;
        }

        // Otherwise, proceed with the default behavior
        return parent::getAttribute($key);
    }


    public function course()
    {
        return $this->belongsTo(CourseNew::class, 'cozid', 'id'); // Assuming `id` is the primary key in course_new
    }

    public function faculty()
    {
        return $this->belongsTo(FacNew::class, 'fac');
    }

    public function department()
    {
        return $this->belongsTo(DeptNew::class, 'dept');
    }
    public function specialization()
    {
        return $this->belongsTo(FieldNew::class, 'field');
    }
}
