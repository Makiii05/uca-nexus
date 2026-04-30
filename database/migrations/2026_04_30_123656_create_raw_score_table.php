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
        Schema::create('raw_score', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grade')->onDelete('cascade');
            $table->foreignId('grade_column_id')->constrained('grade_column')->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['grade_id', 'grade_column_id'], 'uq_rs_grade_column');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_score');
    }
};
