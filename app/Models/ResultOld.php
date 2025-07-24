<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultOld extends Model
{
    use HasFactory;
    protected $table = 'result_old';
    protected $fillable = ['matno', 'code', 'status', 'score', 'WA', 'sec', 'dept'];
    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo(CourseOnline::class, 'code', 'course');
    }

}
