<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; // เรียกใช้โมเดล Course ที่เชื่อมกับตาราง courses

class CourseController extends Controller
{
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่กรอก
        $validated = $request->validate([
            'course_code' => 'required|string|max:10',
            'course_name' => 'required|string|max:255',
            'total_credits' => 'required|integer',
        ]);

        // บันทึกข้อมูลลงในตาราง courses
        Course::create([
            'course_code' => $validated['course_code'],
            'course_name' => $validated['course_name'],
            'total_credits' => $validated['total_credits'],
        ]);

        return redirect()->back()->with('success', 'เพิ่มวิชาเรียบร้อยแล้ว');
    }
    
    public function autocomplete(Request $request)
    {
    $course = Course::where('course_code', $request->course_code)->first();

    if ($course) {
        return response()->json([
            'course_name' => $course->course_name,
            'total_credits' => $course->total_credits,
        ]);
    }

    return response()->json(null);
}

}
