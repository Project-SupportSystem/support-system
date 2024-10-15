<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicRecord extends Model
{
    use HasFactory;

    // ระบุชื่อ Table ให้ชัดเจน
    protected $table = 'academic_records';

    // ระบุฟิลด์ที่สามารถทำ Mass Assignment ได้
    protected $fillable = [
        'student_id',
        'course_id',
        'semester',
        'grade',
        'gpa',
    ];

    /**
     * ความสัมพันธ์กับ Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * ความสัมพันธ์กับ Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Mutator: ทำให้เกรดเป็นตัวพิมพ์ใหญ่เสมอ
     */
    public function setGradeAttribute($value)
    {
        $this->attributes['grade'] = strtoupper($value);
    }

    /**
     * Scope: ดึงข้อมูล AcademicRecord พร้อม Student และ Course
     */
    public function scopeWithStudentAndCourse($query)
    {
        return $query->with(['student', 'course']);
    }
}
