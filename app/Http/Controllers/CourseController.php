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
        // Validate the form input
        $validated = $request->validate([
            'id' => 'required|string|max:8|unique:courses,id',
            'course_name' => 'required|string|max:255',
            'total_credits' => 'required|integer|min:0',
        ]);

        try {
            // Save the course to the database
            $course = Course::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'วิชาใหม่ถูกเพิ่มแล้ว!',
                'course' => $course
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'ไม่สามารถเพิ่มวิชาได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Autocomplete search for course codes.
     */
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');

        $courses = Course::where(function ($query) use ($term) {
            $query->where('course_name', 'LIKE', '%' . $term . '%')
                  ->orWhere('id', 'LIKE', '%' . $term . '%');
        })->take(10)->get(['id', 'course_name', 'total_credits']);
        

    return response()->json($courses);
    }
    
    public function checkDuplicate(Request $request)
    {
        $exists = Course::where('id', $request->id)
                        ->orWhere('course_name', $request->course_name)
                        ->exists();

        return response()->json(['exists' => $exists]);
    }

}
