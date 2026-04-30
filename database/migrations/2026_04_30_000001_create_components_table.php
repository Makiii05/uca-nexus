<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->decimal('percentage', 5, 2);
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['department_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
