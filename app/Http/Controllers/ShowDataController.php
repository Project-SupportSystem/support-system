<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Advisor;
use App\Models\Course;
use App\Models\AcademicRecord;
use App\Models\Document;

class ShowDataController extends Controller
{
    // ฟังก์ชันสำหรับแสดงข้อมูลนักศึกษา
    public function showStudents()
    {
        // ดึงข้อมูลนักศึกษาพร้อมข้อมูลที่เกี่ยวข้อง
        $students = Student::with('user', 'advisor', 'academicRecords.course')->get();

        return view('show_students', compact('students'));
    }

    // ฟังก์ชันสำหรับแสดงข้อมูลอาจารย์ที่ปรึกษา
    public function showAdvisors()
    {
        // ดึงข้อมูลอาจารย์ที่ปรึกษาพร้อมข้อมูลที่เกี่ยวข้อง
        $advisors = Advisor::with('user', 'students')->get();

        return view('show_advisors', compact('advisors'));
    }

    // ฟังก์ชันสำหรับแสดงข้อมูลวิชา
    public function showCourses()
    {
        // ดึงข้อมูลวิชา
        $courses = Course::all();

        return view('show_courses', compact('courses'));
    }
}
