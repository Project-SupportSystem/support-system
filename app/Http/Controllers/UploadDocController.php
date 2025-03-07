<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        $request->validate([
            'titles.*' => 'required|in:สหกิจศึกษา,โปรเจค,internship',
            'files.*.*' => 'required|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
        ]);

        $studentId = Auth::user()->student->id;

        $documentTypeMapping = [
            'สหกิจศึกษา' => 'coop_project',
            'โปรเจค' => 'project',
            'internship' => 'internship'
        ];

        foreach ($request->titles as $index => $documentTitle) {
            if (!isset($documentTypeMapping[$documentTitle])) {
                continue;
            }
            $documentType = $documentTypeMapping[$documentTitle];
            $status = null;

            $filesArray = $request->allFiles();
            if (isset($filesArray['files'][$index])) {
                foreach ($filesArray['files'][$index] as $file) {
                    $originalFileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $safeFileName = Str::random(32) . '.' . $extension;
                    $filePath = $file->storeAs('documents', $safeFileName, 'public');

                    $document = Document::create([
                        'student_id' => $studentId,
                        'document_type' => $documentType,
                        'file_path' => $filePath,
                        'upload_date' => now(),
                        'status' => $status,
                    ]);

                    $uploadedFiles = Cache::get('uploaded_files', []);
                    $uploadedFiles[$document->id] = $originalFileName;
                    Cache::put('uploaded_files', $uploadedFiles);
                }
            }
        }

        return redirect()->back()->with('success', 'เอกสารถูกอัปโหลดสำเร็จแล้ว');
    }

    public function saveStatus(Request $request)
    {
        $studentId = Auth::user()->student->id;

        if ($request->has('kkudq_status') && in_array($request->kkudq_status, ['pass', 'fail'])) {
            $kkudqDoc = Document::where('student_id', $studentId)->where('document_type', 'kku_dq')->first();
            if ($kkudqDoc) {
                $kkudqDoc->update(['status' => $request->kkudq_status, 'upload_date' => now()]);
            } else {
                Document::create([
                    'student_id' => $studentId,
                    'document_type' => 'kku_dq',
                    'file_path' => '',
                    'status' => $request->kkudq_status,
                    'upload_date' => now(),
                ]);
            }
        }

        if ($request->has('kku_kept_status') && in_array($request->kku_kept_status, ['pass', 'fail'])) {
            $kkuKeptDoc = Document::where('student_id', $studentId)->where('document_type', 'kku_kept')->first();
            if ($kkuKeptDoc) {
                $kkuKeptDoc->update(['status' => $request->kku_kept_status, 'upload_date' => now()]);
            } else {
                Document::create([
                    'student_id' => $studentId,
                    'document_type' => 'kku_kept',
                    'file_path' => '',
                    'status' => $request->kku_kept_status,
                    'upload_date' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'บันทึกสถานะสำเร็จแล้ว');
    }

    public function deleteDocument($documentId)
    {
        $document = Document::find($documentId);
        if ($document) {
            Storage::delete($document->file_path);
            $document->delete();

            $uploadedFiles = Cache::get('uploaded_files', []);
            unset($uploadedFiles[$documentId]);
            Cache::put('uploaded_files', $uploadedFiles);

            return redirect()->back()->with('success', 'เอกสารถูกลบเรียบร้อยแล้ว');
        }
        return redirect()->back()->with('error', 'ไม่พบเอกสารที่ต้องการลบ');
    }

    public function getStudentDocuments($studentId, $docType)
    {
        $documents = Document::where('student_id', $studentId)
                              ->where('document_type', $docType)
                              ->get(['file_path', 'id', 'status']);

        $uploadedFiles = Cache::get('uploaded_files', []);

        return response()->json([
            'documents' => $documents->map(function ($doc) use ($uploadedFiles) {
                return [
                    'file_path' => Storage::url($doc->file_path),
                    'file_name' => $uploadedFiles[$doc->id] ?? basename($doc->file_path),
                    'status' => $doc->status,
                ];
            })
        ]);
    }
}