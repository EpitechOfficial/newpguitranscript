<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisExaminer extends Model
{
    use HasFactory;
    protected $table = 'thesis_examiner';

    protected $fillable = [
        'user_id',
        'candidate_id',
        'department_id',
        'faculty',
        'degree_awarded',
        'area_of_specialization',
    ];

    public $timestamps = false;
}

