<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController; // นำเข้า UploadController
use App\Http\Controllers\DatabaseTestController;
use App\Http\Controllers\ShowDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CourseController;




// Route for table_report.blade.php
Route::get('/table-report', function () {
    return view('table_report'); // ไม่ต้องใส่ .blade.php
});

Route::get('/upload-documents', function () {
    return view('uploaddoc'); // ให้แสดงหน้าอัปโหลดเอกสาร
})->name('upload.document');

// Route สำหรับจัดการการอัปโหลดไฟล์
Route::post('/upload-doc', [UploadController::class, 'handleUpload'])->name('upload.handle');

// Route สำหรับเพิ่มข้อมูลลงในฐานข้อมูล
Route::get('/add-data', [DatabaseTestController::class, 'addData']);

// Route สำหรับแสดงข้อมูลจากฐานข้อมูล
Route::get('/show-data', [DatabaseTestController::class, 'showData']);

Route::get('/students', [ShowDataController::class, 'showStudents']);
Route::get('/advisors', [ShowDataController::class, 'showAdvisors']);
Route::get('/courses', [ShowDataController::class, 'showCourses']);

// Route รายงานผลการศึกษา
Route::get('/report', [ReportController::class, 'index'])->name('report');
Route::post('/report/submit', [ReportController::class, 'submit'])->name('report.submit');

// Route การบันทึกวิชาใหม่
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
//route สำหรับ autocomplete
Route::get('/courses/autocomplete', [CourseController::class, 'autocomplete'])->name('courses.autocomplete');


