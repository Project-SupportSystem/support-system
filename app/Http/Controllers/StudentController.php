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
        return view('profile_report', compact('advisors'));
    }

    // บันทึกข้อมูลนักศึกษาใหม่ลงในฐานข้อมูล
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'school_location' => 'nullable|string|max:255',
            'advisor_id' => 'required|exists:advisors,id',
        ]);

        Student::create([
            'user_id' => Auth::id(), // ใช้ user_id ของผู้ใช้ปัจจุบัน
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'school_location' => $request->school_location,
            'advisor_id' => $request->advisor_id,
        ]);

        return redirect()->route('student-profile')->with('success', 'บันทึกข้อมูลสำเร็จ');
    }
}
