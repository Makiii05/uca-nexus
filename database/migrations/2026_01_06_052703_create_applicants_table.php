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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("application_no")->unique();
            //applying for
            $table->string("level");
            $table->string('student_type')->enum(['new', 'transferee'], "new");
            $table->string("year_level")->nullable();
            $table->string("strand")->nullable();
            $table->string("first_program_choice")->nullable();
            $table->string("second_program_choice")->nullable();
            $table->string("third_program_choice")->nullable();
            //personal
            $table->string("last_name");
            $table->string("first_name");
            $table->string("middle_name");
            $table->string("sex")->enum(['Male', 'Female'], "Male");
            $table->string("citizenship");
            $table->string("religion");
            $table->date("birthdate");
            $table->string("place_of_birth");
            $table->string("civil_status")->enum(['Single', 'Married', 'Widow/Widower'], "Single");
            $table->integer("zip_code");
            $table->string("present_address");
            $table->string("permanent_address");
            $table->string("telephone_number")->nullable();
            $table->string("mobile_number")->nullable();
            $table->string("email")->unique();
            //parents
            $table->string("mother_name");
            $table->string("mother_occupation");
            $table->string("mother_contact_number");
            $table->integer("mother_monthly_income");
            $table->string("father_name");
            $table->string("father_occupation");
            $table->string("father_contact_number");
            $table->integer("father_monthly_income");
            $table->string("guardian_name");
            $table->string("guardian_occupation");
            $table->string("guardian_contact_number");
            $table->integer("guardian_monthly_income");
            //educational
            $table->string("elementary_school_name");
            $table->string("elementary_school_address");
            $table->string("elementary_inclusive_years");
            $table->string("junior_school_name");
            $table->string("junior_school_address");
            $table->string("junior_inclusive_years");
            $table->string("senior_school_name");
            $table->string("senior_school_address");
            $table->string("senior_inclusive_years");
            $table->string("college_school_name");
            $table->string("college_school_address");
            $table->string("college_inclusive_years");
            $table->integer("lrn");
            $table->enum('status', ['pending', 'interview', 'exam', 'evaluation', 'rejected', 'accepted', 'admitted'])->default('pending');
            $table->string('academic_year')->nullable();
            $table->string("reject_reason")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
