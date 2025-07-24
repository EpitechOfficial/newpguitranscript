<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;

class Authenticate extends AuthenticatableUser implements Authenticatable
{
    use HasFactory;

    protected $table = 'authenticate';

    protected $fillable = [
        'matric',
        'Surname',
        'Othernames',
        'sex',
        'title',
        'phone',
        'email',
        'register_verified_at',
        'password',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'register_verified_at' => 'datetime',
    ];

    /**
     * Automatically hash password when set
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
