<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\AcademicRecord;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('register_courses', compact('courses'));
    }

    public function getAcademicRecords(Request $request)
    {
        $request->validate(['semester' => 'required|string']);

        $records = AcademicRecord::where('student_id', Auth::user()->student->id)
            ->where('semester', $request->semester)
            ->with('course')
            ->get();
        return response()->json($records);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'semester' => 'required|string',
            'id' => 'required|array',
            'id.*' => 'required|string|exists:courses,id',
            'grade' => 'required|array',
            'grade.*' => 'required|numeric|between:0,4.0',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::where('id', Auth::user()->student->id)->firstOrFail();
            $existingCourses = AcademicRecord::where('student_id', $student->id)
                ->where('semester', $request->semester)
                ->pluck('course_id')
                ->toArray();

            // คำนวณหน่วยกิตรวมที่มีอยู่แล้วใน semester นี้
            $existingCredits = AcademicRecord::where('student_id', $student->id)
                ->where('semester', $request->semester)
                ->join('courses', 'academic_records.course_id', '=', 'courses.id')
                ->sum('courses.total_credits');

            $newCredits = 0;
            foreach ($request->id as $courseId) {
                $course = Course::where('id', $courseId)->firstOrFail();
                $newCredits += $course->total_credits;
            }

            if ($existingCredits + $newCredits > 22) {
                throw new \Exception("หน่วยกิตรวม ($existingCredits + $newCredits) เกิน 22 หน่วยกิต");
            }

            foreach ($request->id as $index => $courseId) {
                if (in_array($courseId, $existingCourses)) {
                    throw new \Exception("วิชารหัส $courseId มีอยู่ในระบบแล้วในภาคการศึกษา " . $request->semester);
                }

                $course = Course::where('id', $courseId)->firstOrFail();
                $grade = $request->grade[$index];

                AcademicRecord::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'semester' => $request->semester,
                    'grade' => $this->convertGPAtoGrade($grade),
                    'gpa' => $grade
                ]);

                $existingCourses[] = $courseId;
            }

            DB::commit();
            return redirect()->route('report')->with('success', 'บันทึกผลการเรียนสำเร็จ!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateCourse(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|string|exists:courses,id',
            'grade' => 'required|numeric|between:0,4.0'
        ]);

        try {
            $record = AcademicRecord::where('student_id', Auth::user()->student->id)
                ->where('id', $id)
                ->first();

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลวิชาที่ต้องการแก้ไข หรือคุณไม่มีสิทธิ์แก้ไข'], 404);
            }

            $record->course_id = $request->course_id;
            $record->gpa = $request->grade;
            $record->grade = $this->convertGPAtoGrade($request->grade);
            $record->save();

            return response()->json(['success' => true, 'message' => 'แก้ไขข้อมูลเรียบร้อย']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล: ' . $e->getMessage()], 500);
        }
    }

    public function deleteCourse($id)
    {
        try {
            $record = AcademicRecord::where('student_id', Auth::user()->student->id)
                ->where('id', $id)
                ->first();

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลวิชาที่ต้องการลบ หรือคุณไม่มีสิทธิ์ลบ'], 404);
            }

            $record->delete();

            return response()->json(['success' => true, 'message' => 'ลบข้อมูลเรียบร้อย']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage()], 500);
        }
    }

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