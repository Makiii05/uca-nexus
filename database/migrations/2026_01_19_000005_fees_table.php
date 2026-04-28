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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('description');
            $table->decimal('amount', 8, 2);
            $table->text('type')->nullable();
            $table->float('month_to_pay')->nullable();
            $table->text('group');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('academic_term_id')->nullable()->constrained('academic_terms')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
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
