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
        Schema::table('admissions', function (Blueprint $table) {
            // Make score and evaluation columns nullable
            $table->string('interview_score')->nullable()->change();
            $table->text('interview_remark')->nullable()->change();
            $table->string('math_score')->nullable()->change();
            $table->string('science_score')->nullable()->change();
            $table->string('english_score')->nullable()->change();
            $table->string('filipino_score')->nullable()->change();
            $table->string('abstract_score')->nullable()->change();
            $table->text('exam_score')->nullable()->change();
            $table->string('final_score')->nullable()->change();
            $table->string('evaluated_by')->nullable()->change();
            $table->string('evaluated_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            // Revert to non-nullable
            $table->string('interview_score')->nullable(false)->change();
            $table->text('interview_remark')->nullable(false)->change();
            $table->string('math_score')->nullable(false)->change();
            $table->string('science_score')->nullable(false)->change();
            $table->string('english_score')->nullable(false)->change();
            $table->string('filipino_score')->nullable(false)->change();
            $table->string('abstract_score')->nullable(false)->change();
            $table->text('exam_score')->nullable(false)->change();
            $table->string('final_score')->nullable(false)->change();
            $table->string('evaluated_by')->nullable(false)->change();
            $table->string('evaluated_at')->nullable(false)->change();
        });
    }
};
