<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // ระบุชื่อ Table ให้ชัดเจน
    protected $table = 'courses';

    // ฟิลด์ที่สามารถทำ Mass Assignment ได้
    protected $fillable = [
        'course_code',
        'course_name',
        'total_credits',
    ];

    /**
     * ความสัมพันธ์กับ AcademicRecord: 
     * Course หนึ่งสามารถมีหลาย AcademicRecord
     */
    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }
}
