<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
        
            $table->string('student_id', 10);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            
            // เปลี่ยน course_id เป็น string(8) เพื่อให้ตรงกับ id ของ courses
            $table->string('course_id', 8);
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            
            $table->string('semester');
            $table->string('grade', 2);
            $table->decimal('gpa', 3, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_records');
    }
}
