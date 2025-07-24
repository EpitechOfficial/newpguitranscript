<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;
    protected $table = 'studentrecord';

    public function getAttribute($key)
    {
        // If the attribute being accessed is 'status', return null
        if ($key === 'specialization' ) {
            return null;
        }

        // Otherwise, proceed with the default behavior
        return parent::getAttribute($key);
    }
}
