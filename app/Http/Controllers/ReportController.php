<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\AcademicRecord;

class ReportController extends Controller
{
    public function index()
    {
        // ดึงรายวิชาทั้งหมดมาแสดงในฟอร์ม
        $courses = Course::all();
        return view('register_courses', compact('courses'));
    }

    public function submit(Request $request)
    {
        // ตรวจสอบข้อมูลที่ส่งมา
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'grade' => 'required|in:A,B+,B,C+,C,D+,D,F',
        ]);

        // บันทึกผลการเรียนลงในตาราง academic_records
        AcademicRecord::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'grade' => $request->grade,
        ]);

        return redirect()->back()->with('success', 'บันทึกผลการเรียนสำเร็จ!');
    }
}
