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
        Schema::table('applicants', function (Blueprint $table) {
            // Modify existing columns to be foreign keys
            $table->unsignedBigInteger('strand')->nullable()->change();
            $table->unsignedBigInteger('first_program_choice')->nullable()->change();
            $table->unsignedBigInteger('second_program_choice')->nullable()->change();
            $table->unsignedBigInteger('third_program_choice')->nullable()->change();

            // Add foreign key constraints
            $table->foreign('strand')->references('id')->on('programs')->onDelete('set null');
            $table->foreign('first_program_choice')->references('id')->on('programs')->onDelete('set null');
            $table->foreign('second_program_choice')->references('id')->on('programs')->onDelete('set null');
            $table->foreign('third_program_choice')->references('id')->on('programs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['strand']);
            $table->dropForeign(['first_program_choice']);
            $table->dropForeign(['second_program_choice']);
            $table->dropForeign(['third_program_choice']);

            // Revert columns back to string
            $table->string('strand')->nullable()->change();
            $table->string('first_program_choice')->nullable()->change();
            $table->string('second_program_choice')->nullable()->change();
            $table->string('third_program_choice')->nullable()->change();
        });
    }
};
