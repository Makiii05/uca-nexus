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
        // student_guardians
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_guardians');
    }
};
