<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // ใช้ student_id เป็น primary key
    public $incrementing = false; // ตั้งค่าเป็น false เพราะ student_id ไม่ใช่ auto-increment
    protected $keyType = 'string'; // ถ้า student_id เป็น string

    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'phone',
        'school_location',
        'advisor_id',
    ];

    public function calculateGPA()
    {
        // ดึงข้อมูล academic records ทั้งหมดของนักศึกษาคนนี้
        $records = $this->academicRecords;

        if ($records->isEmpty()) {
            return null; // ถ้าไม่มีข้อมูลวิชาให้คืนค่า null
        }

        $totalCredits = 0;
        $totalPoints = 0;

        // วนลูปเพื่อคำนวณคะแนนเกรดรวม (GPA)
        foreach ($records as $record) {
            $credit = $record->course->total_credits ?? 0; // ใช้จำนวนหน่วยกิตจาก course
            $gradePoints = match (strtoupper($record->grade)) {
                'A'  => 4.0,
                'B+' => 3.5,
                'B'  => 3.0,
                'C+' => 2.5,
                'C'  => 2.0,
                'D+' => 1.5,
                'D'  => 1.0,
                'F'  => 0.0,
                default => 0.0,
            };

            $totalCredits += $credit;
            $totalPoints += $gradePoints * $credit;
        }

        return $totalCredits > 0 ? $totalPoints / $totalCredits : 0.0;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
