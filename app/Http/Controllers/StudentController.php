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
        'student_id' => 'required|string|max:10|unique:students,id', // ยังคงตรวจสอบความซ้ำ
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
}
