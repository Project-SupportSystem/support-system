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

            $table->string('student_id', 10); // ขนาดสอดคล้องกับ students table
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->enum('document_type', ['transcript', 'KEPT_result', 'DQ_result', 'internship']);
            $table->string('file_path');
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
