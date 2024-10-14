<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // ระบุฟิลด์ที่สามารถเพิ่มลงในฐานข้อมูลได้
    protected $fillable = ['course_code', 'course_name', 'total_credits'];
}
