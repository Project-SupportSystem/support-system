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
        // ตรวจสอบและเพิ่มข้อมูลอาจารย์อัตโนมัติถ้ายังไม่มี
        $this->ensureAdvisorExists();

        $advisors = Advisor::all(); // ดึงข้อมูลอาจารย์ทั้งหมด
        $student = Student::where('user_id', Auth::id())->first();
        return view('profile_report', compact('advisors', 'student'));
    }

    public function showReport()
    {   
        // ตรวจสอบและเพิ่มข้อมูลอาจารย์อัตโนมัติเมื่อล็อกอิน
        $this->ensureAdvisorExists();

        // ดึงข้อมูลอาจารย์ที่ล็อกอิน
        $advisor = Advisor::where('user_id', Auth::id())->first();

        // ดึงเฉพาะนักศึกษาที่อาจารย์คนนี้เป็นที่ปรึกษา
        $students = Student::where('advisor_id', $advisor->id)
                           ->with(['academicRecords.course', 'documents'])
                           ->get();

        $academicRecords = [];
        foreach ($students as $student) {
            $academicRecords[$student->id] = $student->academicRecords()->with('course')->get();
        }

        // นับจำนวนนักศึกษาที่อาจารย์คนนี้ดูแล (ควรเท่ากับ $students->count())
        $studentCount = $students->count();

        return view('table_report', compact('students', 'academicRecords', 'advisor', 'studentCount'));
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
            'province' => 'nullable|string|max:255',
            'advisor_id' => 'required|exists:advisors,id',
        ]);
        $student->update($request->only(['id', 'first_name', 'phone', 'school_location', 'province', 'advisor_id']));
        return redirect()->route('student-profile')->with('success', 'ข้อมูลนักศึกษาถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:10|unique:students,id',
            'first_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'school_location' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'advisor_id' => 'required|exists:advisors,id',
        ]);
        Student::create(array_merge($request->only(['id', 'first_name', 'phone', 'school_location', 'province', 'advisor_id']), ['user_id' => Auth::id()]));
        return redirect()->route('student-profile')->with('success', 'บันทึกข้อมูลนักศึกษาสสำเร็จ');
    }

    public function getAdvisors()
    {
        $advisors = Advisor::all(); // ดึงข้อมูลอาจารย์ทั้งหมด
        return response()->json($advisors); // ตรวจสอบว่า return JSON ถูกต้อง
    }

    public function editAdvisorName(Request $request)
    {
        $advisor = Advisor::where('user_id', Auth::id())->first();
        if (!$advisor) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลอาจารย์');
        }
        $request->validate(['first_name' => 'required|string|max:255']);
        $advisor->update(['first_name' => $request->first_name]);
        return redirect()->route('table_report')->with('success', 'แก้ไขข้อมูลอาจารย์สำเร็จ');
    }

    protected function ensureAdvisorExists()
    {
        $user = Auth::user();
        if ($user->role === 'advisor' && !Advisor::where('user_id', $user->id)->exists()) {
            Advisor::create([
                'first_name' => $user->username, // ใช้ username เป็นชื่อเริ่มต้น
                'user_id' => $user->id,
            ]);
        }
    }
}