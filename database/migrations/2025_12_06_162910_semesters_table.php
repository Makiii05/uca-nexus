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
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('code');
            $table->text('description');
            $table->string('type')->default('semester'); // 'semester' or 'full year'
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');
            $table->text('academic_year');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->enum(['active', 'inactive'], "active");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
