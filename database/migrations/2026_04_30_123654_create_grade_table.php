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
        Schema::dropIfExists('grade');
        Schema::create('grade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_offering_id')->constrained('teacher_offerings')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('period');
            $table->decimal('initial_grade', 5, 2)->nullable();
            $table->decimal('period_grade', 5, 2)->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, finalized
            $table->dateTime('submitted_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('finalized_at')->nullable();
            $table->timestamps();
            $table->unique(['teacher_offering_id', 'student_id', 'period'], 'uq_grade_offering_student_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade');
    }
};
