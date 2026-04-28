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
        // student_academic_history
        Schema::create('student_academic_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string("elementary_school_name");
            $table->string("elementary_school_address");
            $table->string("elementary_inclusive_years");
            $table->string("junior_school_name");
            $table->string("junior_school_address");
            $table->string("junior_inclusive_years");
            $table->string("senior_school_name");
            $table->string("senior_school_address");
            $table->string("senior_inclusive_years");
            $table->string("college_school_name")->nullable();
            $table->string("college_school_address")->nullable();
            $table->string("college_inclusive_years")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_academic_histories');
    }
};
