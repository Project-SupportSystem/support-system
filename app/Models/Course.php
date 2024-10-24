<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // ระบุ Primary Key เป็น 'id' ที่เป็น String
    protected $primaryKey = 'id';  
    public $incrementing = false;  // ปิดการเพิ่มค่าอัตโนมัติ
    protected $keyType = 'string'; // ระบุว่า Primary Key เป็น String

    // ฟิลด์ที่อนุญาตให้ทำ Mass Assignment
    protected $fillable = [
        'id',           // ID วิชาที่เป็น Primary Key
        'course_name',  
        'total_credits',
    ];

    /**
     * ความสัมพันธ์กับ AcademicRecord: 
     * Course หนึ่งสามารถมีหลาย AcademicRecord
     */
    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }
}
