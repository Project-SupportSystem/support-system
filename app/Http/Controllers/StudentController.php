<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function create()
    {
        $advisors = Advisor::all();
        $student = Student::where('user_id', Auth::id())->first();
        return view('profile_report', compact('advisors', 'student'));
    }

    public function showReport()
    {   
        $students = Student::with(['academicRecords.course', 'documents'])->get();
        $academicRecords = [];
        foreach ($students as $student) {
            $academicRecords[$student->id] = $student->academicRecords()->with('course')->get();
        }
        return view('table_report', compact('students', 'academicRecords'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        if ($student->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้');
        }

        $request->validate([
            'student_id' => 'required|string|max:10|unique:students,id,' . $id,
            'first_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'school_location' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255', // เพิ่มการ validate สำหรับ province
            'advisor_id' => 'required|exists:advisors,id',
        ]);

        $student->update([
            'id' => $request->student_id,
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'school_location' => $request->school_location,
            'province' => $request->province, // อัปเดตข้อมูล province
            'advisor_id' => $request->advisor_id,
        ]);

        return redirect()->route('student-profile')->with('success', 'ข้อมูลนักศึกษาถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:10|unique:students,id',
            'first_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'school_location' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255', // เพิ่มการ validate สำหรับ province
            'advisor_id' => 'required|exists:advisors,id',
        ]);

        Student::create([
            'id' => $request->student_id,
            'user_id' => Auth::id(),
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'school_location' => $request->school_location,
            'province' => $request->province, // เพิ่มการบันทึก province
            'advisor_id' => $request->advisor_id,
        ]);

        return redirect()->route('student-profile')->with('success', 'บันทึกข้อมูลนักศึกษาสำเร็จ');
    }
}