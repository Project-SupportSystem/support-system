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
        // ตรวจสอบความถูกต้องของข้อมูลจากฟอร์ม
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'semester' => 'required|string',
            'course_code.*' => 'required|string|exists:courses,course_code',
            'grade.*' => 'required|in:A,B+,B,C+,C,D+,D,F',
        ]);

        // ลูปบันทึกผลการเรียนของแต่ละวิชา
        foreach ($request->course_code as $index => $courseCode) {
            $course = Course::where('course_code', $courseCode)->first();

            // บันทึกข้อมูลแต่ละวิชาลงในตาราง academic_records
            AcademicRecord::create([
                'student_id' => $request->student_id,
                'course_id' => $course->id,
                'semester' => $request->semester,
                'grade' => $request->grade[$index],
            ]);
        }

        return redirect()->back()->with('success', 'บันทึกผลการเรียนสำเร็จ!');
    }
}
