<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\AcademicRecord;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ต้องมีการใช้ DB

class ReportController extends Controller
{
    public function index()
    {
        // ดึงรายวิชาทั้งหมดมาแสดงในฟอร์ม
        $courses = Course::all();
        return view('register_courses', compact('courses'));
    }
    public function getAcademicRecords(Request $request)
    {
        $request->validate(['semester' => 'required|string']);

        // ดึงข้อมูลประวัติการลงทะเบียนจาก AcademicRecords ตามนักเรียนและภาคการศึกษาที่เลือก
        $records = AcademicRecord::where('student_id', Auth::user()->student->id)
            ->where('semester', $request->semester)
            ->with('course') // ร่วมข้อมูลวิชา
            ->get();
        return response()->json($records);
    }

    public function submit(Request $request)
    {
        // dd($request->all());
        
        // ปรับปรุง validation
        $request->validate([
            'semester' => 'required|string',
            'course_code.*' => 'required|string|exists:courses,course_code',
            'grade.*' => 'required|numeric|between:0,4.0',
            'total_credits.*' => 'required|numeric|min:1',
            'course_name.*' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $student = Student::where('id', Auth::user()->student->id)->firstOrFail();

            foreach ($request->id as $index => $id) {
                $course = Course::where('id', $id)->firstOrFail();
                
                // ตรวจสอบข้อมูลซ้ำ
                $existingRecord = AcademicRecord::where([
                    'student_id' => $student->id,
                    'id' => $course->id,
                    'semester' => $request->semester
                ])->first();

                if ($existingRecord) {
                    throw new \Exception("วิชา {$id} มีการลงทะเบียนในภาคการศึกษา {$request->semester} แล้ว");
                }

                // คำนวณ GPA จากเกรดที่ส่งมา
                $grade = $request->grade[$index];
                
                // บันทึกข้อมูล
                AcademicRecord::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'semester' => $request->semester,
                    'grade' => $this->convertGPAtoGrade($grade), // แปลงกลับเป็นเกรดตัวอักษร
                    'gpa' => $grade
                ]);
            }

            DB::commit();
            return redirect()->route('report')->with('success', 'บันทึกผลการเรียนสำเร็จ!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function updateCourse(Request $request, $id)
    {
        $request->validate([
            'course_code' => 'required|string|exists:courses,course_code',
            'grade' => 'required|numeric|between:0,4.0'
        ]);

        try {
            $record = AcademicRecord::where('student_id', Auth::user()->student->id)
                ->where('id', $id)
                ->firstOrFail();

            $record->course_code = $request->course_code;
            $record->gpa = $request->grade;
            $record->grade = $this->convertGPAtoGrade($request->grade);
            $record->save();

            return response()->json(['success' => true, 'message' => 'แก้ไขข้อมูลเรียบร้อย']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล'], 500);
        }
    }
    public function deleteCourse($id)
    {
        try {
            $record = AcademicRecord::where('student_id', Auth::user()->student->id)
                ->where('id', $id)
                ->firstOrFail();

            $record->delete();

            return response()->json(['success' => true, 'message' => 'ลบข้อมูลเรียบร้อย']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล'], 500);
        }
    }

    // เพิ่มฟังก์ชันแปลง GPA เป็นเกรด
    private function convertGPAtoGrade($gpa)
    {
        if ($gpa == 4.0) return 'A';
        if ($gpa == 3.5) return 'B+';
        if ($gpa == 3.0) return 'B';
        if ($gpa == 2.5) return 'C+';
        if ($gpa == 2.0) return 'C';
        if ($gpa == 1.5) return 'D+';
        if ($gpa == 1.0) return 'D';
        return 'F';
    }
}
