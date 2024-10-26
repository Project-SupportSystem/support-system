<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // แสดงฟอร์มกรอกข้อมูลนักศึกษา
    public function create()
    {
        $advisors = Advisor::all(); // ดึงข้อมูลที่ปรึกษาเพื่อแสดงในฟอร์ม
        $student = Student::where('user_id', Auth::id())->first(); // ดึงข้อมูลนักศึกษา

        return view('profile_report', compact('advisors', 'student'));
    }

    // บันทึกข้อมูลนักศึกษาใหม่ลงในฐานข้อมูล
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:10|unique:students,id', // ตรวจสอบรหัสซ้ำ
            'first_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'school_location' => 'nullable|string|max:255',
            'advisor_id' => 'required|exists:advisors,id',
        ]);

        // สร้างข้อมูลนักศึกษาใหม่
        Student::create([
            'id' => $request->student_id, // ตั้งค่า id จาก student_id
            'user_id' => Auth::id(), // ใช้ user_id ของผู้ใช้ปัจจุบัน
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'school_location' => $request->school_location,
            'advisor_id' => $request->advisor_id,
        ]);

        return redirect()->route('student-profile')->with('success', 'บันทึกข้อมูลสำเร็จ');
    }

    // ฟังก์ชันอัปเดตข้อมูลนักศึกษา
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id); // ค้นหานักศึกษาตาม ID ที่ส่งมา

        // ตรวจสอบค่าที่กรอกมาให้ถูกต้อง
        $request->validate([
            'student_id' => 'required|string|max:10|unique:students,id,' . $id, // ตรวจสอบว่ารหัสไม่ซ้ำกับนักศึกษาอื่น
            'first_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'school_location' => 'nullable|string|max:255',
            'advisor_id' => 'required|exists:advisors,id',
        ]);

        // อัปเดตข้อมูลนักศึกษา รวมถึง student_id
        $student->update([
            'id' => $request->student_id,
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'school_location' => $request->school_location,
            'advisor_id' => $request->advisor_id,
        ]);

        return redirect()->back()->with('success', 'ข้อมูลนักศึกษาถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function showReport()
    {   
        // ดึงข้อมูลนักศึกษาทั้งหมดจากฐานข้อมูล
        $students = Student::with(['academicRecords.course'])->get(); // ดึงข้อมูลนักศึกษาและวิชา

        // ส่งข้อมูลไปยัง view 'table_report'
        return view('table_report', compact('students'));
    }
}
