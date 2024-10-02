<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController; // นำเข้า UploadController


// Route for table_report.blade.php
Route::get('/table-report', function () {
    return view('table_report'); // ไม่ต้องใส่ .blade.php
});

Route::get('/upload-doc', function () {
    return view('uploaddoc'); // ให้แสดงหน้าอัปโหลดเอกสาร
});

// Route สำหรับจัดการการอัปโหลดไฟล์
Route::post('/upload-doc', [UploadController::class, 'handleUpload'])->name('upload.handle');

