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
        $student = Auth::user()->student;
        $documents = Document::where('student_id', $student->id)->get();

        return view('uploaddoc', compact('documents', 'student'));
    }

    /**
     * Handle the document upload and store data in the database.
     */
    public function uploadDocument(Request $request)
    {
        // Validate input
        $request->validate([
            'titles.*' => 'required|in:สหกิจศึกษา,โปรเจค,KKUDQ,KEPT,internship,transcript,grade_proof',
            'files.*.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'kept_status' => 'nullable|in:pass,fail',
            'kkudq_status' => 'nullable|in:pass,fail',
        ]);

        $student = Auth::user()->student->id;

        $documentTypeMapping = [
            'สหกิจศึกษา' => 'coop_project',
            'โปรเจค' => 'project',
            'KKUDQ' => 'kku_dq',
            'KEPT' => 'kku_kept',
            'internship' => 'internship',
            'transcript' => 'transcript',
            'grade_proof' => 'grade_proof',
        ];

        // Handle file upload
        foreach ($request->titles as $index => $documentTitle) {
            if (!isset($documentTypeMapping[$documentTitle])) {
                continue; // Skip invalid titles
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
                        'status' => $documentType === 'kku_dq' || $documentType === 'kku_kept' ? $request->get("{$documentType}_status") : null,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'เอกสารถูกอัปโหลดสำเร็จแล้ว');
    }

    /**
     * Retrieve student documents based on type.
     */
    public function getStudentDocuments($studentId, $docType)
    {
        $documents = Document::where('student_id', $studentId)
            ->where('document_type', $docType)
            ->get(['file_path', 'id', 'status']);

        return response()->json([
            'documents' => $documents->map(function ($doc) {
                return [
                    'file_path' => Storage::url($doc->file_path),
                    'file_name' => basename($doc->file_path),
                    'status' => $doc->status,
                ];
            })
        ]);
    }
}
