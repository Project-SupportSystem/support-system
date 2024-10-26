<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AcademicRecordController;
use App\Http\Controllers\UploadDocController;




// Group เส้นทางที่ต้องการ Auth
Route::middleware('auth')->group(function () {
    Route::get('/table-report', function () {
        return view('table_report');
    })->name('table_report');
    Route::get('/table-report', [StudentController::class, 'showReport'])->name('table_report');


    Route::get('/register-courses', function () {
        return view('register_courses');
    })->name('register_courses');

    Route::get('/student-profile', [StudentController::class, 'create'])->name('student-profile');
    Route::post('/student-profile', [StudentController::class, 'store'])->name('student.store');
    Route::put('/student/{id}', [StudentController::class, 'update'])->name('student.update');


    Route::get('/upload-documents', function () {
        return view('uploaddoc');
    })->name('upload.document');

    Route::get('/upload-doc', [UploadDocController::class, 'index'])->name('upload.doc');
    Route::post('/upload-doc', [UploadDocController::class, 'uploadDocument'])->name('upload.doc.store');
    Route::get('/get-student-documents/{studentId}/{docType}', [UploadDocController::class, 'getStudentDocuments']);

});



// Route บันทึกผลการเรียน
Route::get('/report', [ReportController::class, 'index'])->name('report');
Route::post('/report/submit', [ReportController::class, 'submit'])->name('report.submit');
Route::get('/report/academic-records', [ReportController::class, 'getAcademicRecords'])->name('report.academic.records');


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



