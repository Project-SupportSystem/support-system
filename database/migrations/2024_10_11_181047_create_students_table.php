<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            // เปลี่ยนจาก $table->id(); เป็น string สำหรับเก็บ ID ของนักเรียนที่ผู้ใช้กรอกเอง
            $table->string('id', 10)->primary();  // ใช้ string สำหรับเก็บ student ID และตั้งเป็น primary key
            $table->foreignId('user_id')->constrained('users')->unique()->onDelete('cascade');
            $table->string('first_name');
            $table->string('phone', 10)->nullable();
            $table->string('school_location')->nullable();
            $table->foreignId('advisor_id')->constrained('advisors')->onDelete('cascade');
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
        Schema::dropIfExists('students');
    }
}
