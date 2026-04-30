<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subject_offerings', function (Blueprint $table) {
            $table->foreignId('grading_id')
                ->nullable()
                ->after('level_id')
                ->constrained('grading_systems')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('subject_offerings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('grading_id');
        });
    }
};
