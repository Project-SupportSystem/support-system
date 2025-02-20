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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'กรุณาล็อกอินก่อน');
        }

        $user = Auth::user();
        if (is_null($user->student)) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลนักศึกษาที่เชื่อมโยงกับบัญชีนี้');
        }

        $studentId = $user->student->id;
        $documents = Document::where('student_id', $studentId)->get();

        return view('uploaddoc', compact('documents'));
    }

    /**
     * จัดการการอัปโหลดเอกสารและบันทึกข้อมูลในฐานข้อมูล
     */
    public function uploadDocument(Request $request)
    {
        try {
            $user = Auth::user();
            if (is_null($user->student)) {
                return redirect()->back()->with('error', 'ไม่พบข้อมูลนักศึกษาที่เชื่อมโยงกับบัญชีนี้');
            }

            $studentId = $user->student->id;

            // Validation
            $request->validate([
                'titles.*' => 'required|in:สหกิจศึกษา,โปรเจค,KKUDQ,KKU KEPT',
                'files.0.*' => 'required_if:titles.0,สหกิจศึกษา,โปรเจค|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'kkudq_status' => 'required_if:titles.1,KKUDQ|in:pass,fail',
                'kept_status' => 'required_if:titles.2,KKU KEPT|in:pass,fail',
            ], [
                'titles.*.required' => 'กรุณาเลือกประเภทเอกสาร',
                'files.0.*.required_if' => 'กรุณาอัปโหลดไฟล์สำหรับสหกิจศึกษา/โปรเจค',
                'kkudq_status.required_if' => 'กรุณาระบุสถานะ KKUDQ',
                'kept_status.required_if' => 'กรุณาระบุสถานะ KKU KEPT',
            ]);

            // บันทึกข้อมูลเอกสาร
            foreach ($request->titles as $index => $title) {
                if ($title === 'สหกิจศึกษา' || $title === 'โปรเจค') {
                    if ($request->hasFile("files.0")) {
                        foreach ($request->file("files.0") as $file) {
                            $filePath = $file->store('documents', 'public');
                            Document::create([
                                'student_id' => $studentId,
                                'title' => $title,
                                'file_path' => $filePath,
                                'original_file_name' => $file->getClientOriginalName(),
                            ]);
                        }
                    }
                } elseif ($title === 'KKUDQ') {
                    Document::create([
                        'student_id' => $studentId,
                        'title' => $title,
                        'status' => $request->kkudq_status,
                    ]);
                } elseif ($title === 'KKU KEPT') {
                    Document::create([
                        'student_id' => $studentId,
                        'title' => $title,
                        'status' => $request->kept_status,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'อัปโหลดเอกสารสำเร็จ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ลบเอกสาร
     */
    public function deleteDocument($id)
    {
        $document = Document::findOrFail($id);
        if ($document->student_id !== Auth::user()->student->id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ลบเอกสารนี้');
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();

        return redirect()->back()->with('success', 'ลบเอกสารสำเร็จ');
    }
}