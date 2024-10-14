<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController; // นำเข้า UploadController
use App\Http\Controllers\DatabaseTestController;
use App\Http\Controllers\ShowDataController;



// Route for table_report.blade.php
Route::get('/table-report', function () {
    return view('table_report'); // ไม่ต้องใส่ .blade.php
});

Route::get('/upload-doc', function () {
    return view('uploaddoc'); // ให้แสดงหน้าอัปโหลดเอกสาร
});

// Route สำหรับจัดการการอัปโหลดไฟล์
Route::post('/upload-doc', [UploadController::class, 'handleUpload'])->name('upload.handle');

// Route สำหรับเพิ่มข้อมูลลงในฐานข้อมูล
Route::get('/add-data', [DatabaseTestController::class, 'addData']);

// Route สำหรับแสดงข้อมูลจากฐานข้อมูล
Route::get('/show-data', [DatabaseTestController::class, 'showData']);

Route::get('/students', [ShowDataController::class, 'showStudents']);
Route::get('/advisors', [ShowDataController::class, 'showAdvisors']);
Route::get('/courses', [ShowDataController::class, 'showCourses']);