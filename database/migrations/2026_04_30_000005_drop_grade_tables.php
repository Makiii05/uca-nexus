<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('raw_scores');
        Schema::dropIfExists('grade_columns');
        Schema::dropIfExists('grades');
    }

    public function down(): void
    {
        Schema::create('grade_columns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->foreignId('offering_id')->nullable()->constrained('subject_offerings')->nullOnDelete();
            $table->foreignId('academic_term_id')->nullable()->constrained('academic_terms')->nullOnDelete();
            $table->string('grading_period');
            $table->enum('component_type', ['ww', 'pt', 'qa'])->default('ww');
            $table->integer('column_number');
            $table->decimal('highest_possible_score');
            $table->string('description')->nullable();
        });

        Schema::create('raw_scores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('column_id')->nullable()->constrained('grade_columns')->nullOnDelete();
            $table->decimal('score', 7, 2)->nullable();
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->foreignId('term_id')->nullable()->constrained('academic_terms')->nullOnDelete();
            $table->string('grading_period')->nullable();
            $table->decimal('ww_whole', 5, 2)->nullable();
            $table->decimal('pt_whole', 5, 2)->nullable();
            $table->decimal('qa_whole', 5, 2)->nullable();
            $table->decimal('ww_total', 5, 2)->nullable();
            $table->decimal('pt_total', 5, 2)->nullable();
            $table->decimal('qa_total', 5, 2)->nullable();
            $table->decimal('ww_percent', 5, 2)->nullable();
            $table->decimal('pt_percent', 5, 2)->nullable();
            $table->decimal('qa_percent', 5, 2)->nullable();
            $table->decimal('initial_grade', 5, 2)->nullable();
            $table->decimal('period_grade', 5, 2)->nullable();
            $table->enum('status', ['pending', 'draft', 'submitted', 'approved', 'finalized'])->default('draft');
            $table->boolean('is_direct_input')->default(true);
            $table->dateTime('submtted_at')->nullable();
            $table->dateTime('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('fnalized_at')->nullable();
            $table->string('remarks')->nullable();
        });
    }
};
