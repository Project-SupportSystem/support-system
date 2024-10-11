<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Advisor;
use App\Models\Course;
use App\Models\AcademicRecord;
use App\Models\Document;

class DatabaseTestController extends Controller
{
    // ฟังก์ชันสำหรับเพิ่มข้อมูลตัวอย่างลงในฐานข้อมูล
    public function addData()
    {
        // เพิ่มผู้ใช้ใหม่ (บทบาทนักศึกษา)
        $user = User::create([
            'username' => 'student01',
            'role' => 'student',
        ]);

        // เพิ่มข้อมูลนักศึกษาใหม่ที่เชื่อมโยงกับผู้ใช้
        $student = Student::create([
            'user_id' => $user->id,
            'first_name' => 'John Doe',
            'phone' => '0123456789',
            'school_location' => 'Bangkok',
            'advisor_id' => null,
        ]);

        // เพิ่มวิชาใหม่
        $course = Course::create([
            'course_code' => 'IN100000',
            'course_name' => 'Introduction to Programming',
            'total_credits' => 3,
        ]);

        // เพิ่มผลการเรียนใหม่
        AcademicRecord::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester' => '1/2024',
            'grade' => 'A',
            'gpa' => 4.00,
        ]);

        return 'Data added successfully!';
    }

    // ฟังก์ชันสำหรับแสดงข้อมูลทั้งหมดในฐานข้อมูล
    public function showData()
    {
        $students = Student::with('user', 'academicRecords.course')->get();

        return response()->json($students);
    }
}

