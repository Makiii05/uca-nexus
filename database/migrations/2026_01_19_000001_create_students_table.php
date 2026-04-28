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
        // students
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("student_number")->unique();
            $table->bigInteger("lrn");
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');
            $table->string("last_name");
            $table->string("first_name");
            $table->string("middle_name")->nullable();
            $table->enum("sex", ['Male', 'Female'])->default('Male');
            $table->string("citizenship");
            $table->string("religion");
            $table->date("birthdate");
            $table->string("place_of_birth");
            $table->enum("civil_status", ['Single', 'Married', 'Widow/Widower'])->default('Single');
            $table->enum('account_status', ['on', 'off'])->default('on');
            $table->enum('student_type', ['new', 'old'])->default('new');
            $table->foreignId('application_id')->constrained('applicants')->onDelete('cascade');
            $table->enum("status", ['enrolled', 'withdrawn', 'dropped', 'graduated', 'regular', 'irregular'])->default('enrolled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
