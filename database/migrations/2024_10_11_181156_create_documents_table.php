<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Reference to students table with cascade on delete
            $table->string('student_id', 10);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            // Expanded document types to support 5 forms from uploaddoc.blade.php
            $table->enum('document_type', [
                'coop_project',     // สหกิจศึกษา 
                'project',          // โปรเจค
                'kku_dq',           // KKUDQ
                'kku_kept',         // KKU KEPT
                'internship',       // ฝึกงาน
                'transcript'        // ผลการศึกษา
            ]);

            $table->string('file_path');  // Path to document file
            $table->timestamp('upload_date')->useCurrent();
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
        Schema::dropIfExists('documents');
    }
}
