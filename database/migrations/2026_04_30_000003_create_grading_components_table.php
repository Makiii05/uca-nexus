<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
            $table->foreignId('grading_id')->constrained('grading_systems')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['component_id', 'grading_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_components');
    }
};
