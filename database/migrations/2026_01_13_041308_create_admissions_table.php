<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            //interview
            $table->foreignId('interview_schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->string('interview_score');
            $table->text('interview_remark');
            $table->enum('interview_result', ['passed', 'failed', 'pending'])->default('pending');
            //interview
            $table->foreignId('exam_schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->string('math_score');
            $table->string('science_score');
            $table->string('english_score');
            $table->string('filipino_score');
            $table->string('abstract_score');
            $table->text('exam_score');
            $table->enum('exam_result', ['passed', 'failed', 'pending'])->default('pending');
            //eval
            $table->string('final_score');
            $table->enum('decision', ['accepted', 'rejected', 'pending'])->default('pending');
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDeletse('set null');
            $table->foreignId('evaluation_schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->string('evaluated_by');
            $table->string('evaluated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
