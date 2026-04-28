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
        // student_contacts
        Schema::create('student_contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->integer("zip_code");
            $table->string("present_address");
            $table->string("permanent_address");
            $table->string("telephone_number")->nullable();
            $table->string("mobile_number")->nullable();
            $table->string("email")->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_contacts');
    }
};
