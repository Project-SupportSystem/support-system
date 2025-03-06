<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'student_id',
        'document_type',
        'file_path',
        'upload_date',
        'status', // ตรวจสอบว่า status อยู่ใน $fillable
    ];

    protected $casts = [
        'upload_date' => 'datetime',
        'status' => 'string', // ระบุว่า status เป็น string
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}