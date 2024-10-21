<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; // เรียกใช้โมเดล Course ที่เชื่อมกับตาราง courses

class CourseController extends Controller
{
    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'course_code' => 'required|string|max:10',
            'course_name' => 'required|string|max:255',
            'total_credits' => 'required|integer',
        ]);

        // Save the course to the database
        Course::create([
            'course_code' => $validated['course_code'],
            'course_name' => $validated['course_name'],
            'total_credits' => $validated['total_credits'],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'เพิ่มวิชาเรียบร้อยแล้ว');
    }

    /**
     * Autocomplete search for course codes.
     */
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');

        $courses = Course::where('course_code', 'LIKE', '%' . $term . '%')
            ->orWhere('course_name', 'LIKE', '%' . $term . '%')
            ->take(10)
            ->get(['course_code', 'course_name', 'total_credits']); // ดึงเฉพาะฟิลด์ที่จำเป็น

    return response()->json($courses);
    }
}
