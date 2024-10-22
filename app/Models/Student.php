<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // ใช้ student_id เป็น primary key
    public $incrementing = false; // ตั้งค่าเป็น false เพราะ student_id ไม่ใช่ auto-increment
    protected $keyType = 'string'; // ถ้า student_id เป็น string

    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'phone',
        'school_location',
        'advisor_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
