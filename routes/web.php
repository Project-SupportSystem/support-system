<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadDocController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AcademicRecordController;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/register-courses', function () {
        return view('register_courses');
    })->name('register_courses');

    Route::get('/student-profile', [StudentController::class, 'create'])->name('student-profile');
    Route::post('/student-profile', [StudentController::class, 'store'])->name('student.store');
    Route::put('/student/update/{id}', [StudentController::class, 'update'])->name('student.update');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::post('/report/submit', [ReportController::class, 'submit'])->name('report.submit');
    Route::get('/report/academic-records', [ReportController::class, 'getAcademicRecords'])->name('report.academic.records');
    Route::post('/report/update-course/{id}', [ReportController::class, 'updateCourse'])->name('report.update.course');
    Route::delete('/report/delete-course/{id}', [ReportController::class, 'deleteCourse'])->name('report.delete.course');

    Route::get('/upload-document', [UploadDocController::class, 'index'])->name('upload.document');
    Route::post('/upload-doc', [UploadDocController::class, 'uploadDocument'])->name('upload.doc');
    Route::post('/save-status', [UploadDocController::class, 'saveStatus'])->name('save.status');
    Route::delete('/document/{documentId}', [UploadDocController::class, 'deleteDocument'])->name('document.delete');
    Route::get('/delete-document/{documentId}', [UploadDocController::class, 'deleteDocument'])->name('document.delete');
    Route::get('/get-student-documents/{studentId}/{docType}', [UploadDocController::class, 'getStudentDocuments']);

    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/autocomplete', [CourseController::class, 'autocomplete'])->name('courses.autocomplete');
    Route::post('/courses/check-duplicate', [CourseController::class, 'checkDuplicate'])->name('courses.check.duplicate');
});

Route::middleware(['auth', 'role:advisor'])->group(function () {
    Route::get('/table-report', [StudentController::class, 'showReport'])->name('table_report');
    Route::get('/get-advisors', [StudentController::class, 'getAdvisors'])->name('advisor.get');
    Route::post('/edit-advisor-name', [StudentController::class, 'editAdvisorName'])->name('advisor.edit.name');
    Route::get('/get-student-documents/{studentId}/{docType}', [UploadDocController::class, 'getStudentDocuments']);
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/call-back', [SocialiteController::class, 'handleGoogleCallback']);
Route::get('/logout', [SocialiteController::class, 'logout'])->name('logout');