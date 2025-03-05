<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class UploadDocController extends Controller
{
    public function index()
    {
        $studentId = Auth::user()->student->id;
        $documents = Document::where('student_id', $studentId)->get();
        return view('uploaddoc', compact('documents'));
    }

    public function uploadDocument(Request $request)
    {
        try {
            $request->validate([
                'titles.*' => 'required|in:สหกิจศึกษา,โปรเจค,KKUDQ,KEPT,internship,transcript',
                'files.*.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $studentId = Auth::user()->student->id;

            $documentTypeMapping = [
                'สหกิจศึกษา' => 'coop_project',
                'โปรเจค' => 'project',
                'KKUDQ' => 'kku_dq',
                'KEPT' => 'kku_kept',
                'internship' => 'internship',
                'transcript' => 'transcript'
            ];

            foreach ($request->titles as $index => $documentTitle) {
                if (!isset($documentTypeMapping[$documentTitle])) {
                    continue;
                }
                $documentType = $documentTypeMapping[$documentTitle];

                $filesArray = $request->allFiles();
                if (isset($filesArray['files'][$index])) {
                    foreach ($filesArray['files'][$index] as $file) {
                        $filePath = $file->store('documents', 'public');
                        // เก็บชื่อไฟล์จริงใน session หรือ array เพื่อส่งไปยัง Blade
                        $originalFileName = $file->getClientOriginalName();

                        $document = Document::create([
                            'student_id' => $studentId,
                            'document_type' => $documentType,
                            'file_path' => $filePath,
                            'upload_date' => now(),
                        ]);

                        // เก็บชื่อไฟล์จริงใน session หรือ array (ในที่นี้ใช้ session)
                        $uploadedFiles = session('uploaded_files', []);
                        $uploadedFiles[$document->id] = $originalFileName;
                        session(['uploaded_files' => $uploadedFiles]);
                    }
                }
            }

            return redirect()->back()->with('success', 'เอกสารถูกอัปโหลดสำเร็จแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'การอัปโหลดล้มเหลว: ' . $e->getMessage());
        }
    }

    public function deleteDocument($documentId)
    {
        $document = Document::find($documentId);
        if ($document) {
            Storage::delete($document->file_path);
            $document->delete();

            // ลบชื่อไฟล์ออกจาก session
            $uploadedFiles = session('uploaded_files', []);
            unset($uploadedFiles[$documentId]);
            session(['uploaded_files' => $uploadedFiles]);

            return redirect()->back()->with('success', 'เอกสารถูกลบเรียบร้อยแล้ว');
        }
        return redirect()->back()->with('error', 'ไม่พบเอกสารที่ต้องการลบ');
    }
}