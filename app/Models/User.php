<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'role',
    ];

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function advisor()
    {
        return $this->hasOne(Advisor::class);
    }
}
