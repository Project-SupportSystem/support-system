<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DatabaseTestController;
use App\Http\Controllers\ShowDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Auth\SocialiteController;

// Group เส้นทางที่ต้องการ Auth
Route::middleware('auth')->group(function () {
    Route::get('/table-report', function () {
        return view('table_report');
    })->name('table_report');

    Route::get('/register-courses', function () {
        //dd(Auth::user()); // ตรวจสอบข้อมูลผู้ใช้
        return view('register_courses');
    })->name('register_courses');

    Route::get('/upload-documents', function () {
        return view('uploaddoc');
    })->name('upload.document');

    Route::post('/upload-doc', [UploadController::class, 'handleUpload'])->name('upload.handle');
});

// Route เพิ่มข้อมูลและแสดงข้อมูลในฐานข้อมูล
Route::get('/add-data', [DatabaseTestController::class, 'addData']);
Route::get('/show-data', [DatabaseTestController::class, 'showData']);

// Route สำหรับแสดงข้อมูลผู้ใช้
Route::get('/students', [ShowDataController::class, 'showStudents']);
Route::get('/advisors', [ShowDataController::class, 'showAdvisors']);
Route::get('/courses', [ShowDataController::class, 'showCourses']);

// Route รายงานผลการศึกษา
Route::get('/report', [ReportController::class, 'index'])->name('report');
Route::post('/report/submit', [ReportController::class, 'submit'])->name('report.submit');

// Route สำหรับบันทึกวิชาใหม่และ autocomplete
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::get('/courses/autocomplete', [CourseController::class, 'autocomplete'])->name('courses.autocomplete');

// Route Login และ Socialite Login
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/call-back', [SocialiteController::class, 'handleGoogleCallback']);

// Route Logout
Route::get('/logout', [SocialiteController::class, 'logout'])->name('logout');
