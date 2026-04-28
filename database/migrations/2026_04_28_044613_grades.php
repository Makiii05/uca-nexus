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
        Schema::create('grades', function (Blueprint $table) {
            $table->id(); // Primary key
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
            $table->datetime('submtted_at');
            $table->datetime('approved_by');
            $table->datetime('approved_at');
            $table->datetime('fnalized_at');
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
