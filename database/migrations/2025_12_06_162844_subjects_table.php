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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->timestamps();
            $table->text('code');
            $table->text('description');
            $table->integer('unit');
            $table->integer('lech');
            $table->integer('lecu');
            $table->integer('labh');
            $table->integer('labu');
            $table->string('type')->enum(['lab', 'lec', 'lec lab'], null);
            $table->string('status')->enum(['active', 'inactive'], "active");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
