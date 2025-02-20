<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class UploadDocController extends Controller
{
    /**
     * แสดงฟอร์มการอัปโหลดเอกสาร
     */
    public function index()
    {
        $studentId = Auth::user()->student->id;
        // ดึงข้อมูลเอกสารที่อัปโหลดแล้วของนักศึกษา
        $documents = Document::where('student_id', $studentId)->get();
        return view('uploaddoc', compact('documents'));
    }

    /**
     * จัดการการอัปโหลดเอกสารและบันทึกข้อมูลในฐานข้อมูล
     */
    public function uploadDocument(Request $request)
    {
        try {
            // Validate ข้อมูลจากฟอร์ม
            $request->validate([
                'titles.*' => 'required|in:สหกิจศึกษา,โปรเจค,KKUDQ,KEPT,internship,transcript', // ปรับรายการตามที่ต้องการ
                'files.*.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // ตรวจสอบไฟล์ประเภทและขนาด
            ]);
    
            // ดึงรหัสนักศึกษาจากผู้ใช้ที่เข้าสู่ระบบ
            $studentId = Auth::user()->student->id;
    
            // การแมพประเภทเอกสาร
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
    
                // ดึงข้อมูลไฟล์จากฟอร์ม
                $filesArray = $request->allFiles();
                if (isset($filesArray['files'][$index])) {
                    foreach ($filesArray['files'][$index] as $file) {
                        // อัปโหลดไฟล์ไปยัง Storage (Laravel จะเก็บไฟล์ใน storage และเปลี่ยนชื่อไฟล์)
                        $filePath = $file->store('documents', 'public');
    
                        // บันทึกข้อมูลในฐานข้อมูลพร้อมชื่อไฟล์เดิม
                        Document::create([
                            'student_id' => $studentId,
                            'document_type' => $documentType,
                            'file_path' => $filePath,
                            'original_file_name' => $file->getClientOriginalName(), // เก็บชื่อไฟล์เดิม
                            'upload_date' => now(),
                        ]);
                    }
                }
            }
    
            // รีไดเรคกลับไปยังหน้าก่อนหน้านี้พร้อมกับข้อความสำเร็จ
            return redirect()->back()->with('success', 'เอกสารถูกอัปโหลดสำเร็จแล้ว');
        } catch (\Exception $e) {
            // รีไดเรคกลับไปยังหน้าก่อนหน้านี้พร้อมกับข้อความข้อผิดพลาด
            return redirect()->back()->with('error', 'การอัปโหลดล้มเหลว: ' . $e->getMessage());
        }
    }

    /**
     * ดึงข้อมูลเอกสารของนักศึกษา
     */
    public function getStudentDocuments($studentId, $docType)
    {
        // ค้นหาเอกสารในฐานข้อมูลตามรหัสนักศึกษาและประเภทเอกสาร
        $documents = Document::where('student_id', $studentId)
                              ->where('document_type', $docType)
                              ->get(['file_path', 'id']);

        // ส่งข้อมูลกลับในรูปแบบ JSON
        return response()->json([
            'documents' => $documents->map(function ($doc) {
                return [
                    'file_path' => Storage::url($doc->file_path),
                    'file_name' => basename($doc->file_path),
                ];
            })
        ]);
    }

    /**
     * ลบเอกสาร
     */
    public function deleteDocument($documentId)
    {
        // ค้นหาเอกสารตาม ID
        $document = Document::find($documentId);
        
        if ($document) {
            // ลบไฟล์จาก Storage
            Storage::delete($document->file_path);
            
            // ลบข้อมูลเอกสารจากฐานข้อมูล
            $document->delete();
            
            return redirect()->back()->with('success', 'เอกสารถูกลบเรียบร้อยแล้ว');
        }
        
        return redirect()->back()->with('error', 'ไม่พบเอกสารที่ต้องการลบ');
    }
}
