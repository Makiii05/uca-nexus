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
        Schema::dropIfExists('grade_column');
        Schema::create('grade_column', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_offering_id')->constrained('teacher_offerings')->onDelete('cascade');
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
            $table->integer('column_number');
            $table->decimal('highest_score', 5, 2);
            $table->timestamps();
            $table->unique(['teacher_offering_id', 'component_id', 'column_number'], 'uq_gc_offering_comp_col');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_column');
    }
};
