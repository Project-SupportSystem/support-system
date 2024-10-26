<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;


class UploadDocController extends Controller
{
    /**
     * Show the document upload form.
     */
    public function index()
    {
        return view('uploaddoc');
    }

    /**
     * Handle the document upload and store data in the database.
     */
    public function uploadDocument(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'titles.*' => 'required|in:สหกิจศึกษา,โปรเจค,KKUDQ,KEPT,internship,transcript',  // ปรับรายการตามที่ต้องการ
            'files.*.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',  // Validate file type and size
        ]);

        $student = Auth::user()->student->id;  // ดึงรหัสนักศึกษาจากผู้ใช้ที่เข้าสู่ระบบ

        $documentTypeMapping = [
            'สหกิจศึกษา' => 'coop_project',
            'โปรเจค' => 'project',
            'KKUDQ' => 'kku_dq',
            'KEPT' => 'kku_kept',
            'internship' => 'internship',
            'transcript' => 'transcript'
        ];

        // วนลูปไฟล์และบันทึกข้อมูลทีละรายการ
        foreach ($request->titles as $index => $documentTitle) {
            if (!isset($documentTypeMapping[$documentTitle])) {
                continue; // ข้ามกรณีที่ไม่มีการแมพ
            }
            $documentType = $documentTypeMapping[$documentTitle];
    
            $filesArray = $request->allFiles();
            if (isset($filesArray['files'][$index])) {
                foreach ($filesArray['files'][$index] as $file) {
                    $filePath = $file->store('documents', 'public');
                    Document::create([
                        'student_id' => $student,
                        'document_type' => $documentType,
                        'file_path' => $filePath,
                        'upload_date' => now(),
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'เอกสารถูกอัปโหลดสำเร็จแล้ว');
    }
    public function getStudentDocuments($studentId, $docType)
    {
        $documents = Document::where('student_id', $studentId)->where('document_type', $docType)->get(['file_path', 'id']);

        return response()->json([
            'documents' => $documents->map(function ($doc) {
                return [
                    'file_path' => Storage::url($doc->file_path),
                    'file_name' => basename($doc->file_path),
                ];
            })
        ]);
    }
}
