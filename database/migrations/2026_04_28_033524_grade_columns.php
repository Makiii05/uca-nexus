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
        Schema::create('grade_columns', function (Blueprint $table){
            $table->id(); // Primary key
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
