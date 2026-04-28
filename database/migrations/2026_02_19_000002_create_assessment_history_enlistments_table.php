<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_history_enlistments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_history_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->string('description');
            $table->decimal('units', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_history_enlistments');
    }
};
