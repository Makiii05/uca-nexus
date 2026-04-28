<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_history_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_history_id')->constrained()->onDelete('cascade');
            $table->string('student_number');
            $table->string('name');
            $table->string('year_level');
            $table->string('program');
            $table->string('department');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_history_students');
    }
};
