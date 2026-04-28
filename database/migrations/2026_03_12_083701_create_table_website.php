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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("type");
            $table->string("image_url")->nullable();
            $table->string("title")->nullable();
            $table->text("description")->nullable();
            $table->date("event_date")->nullable();
            $table->string("location")->nullable();
            $table->string("days")->nullable();
            $table->boolean("is_open")->nullable()->default(true);
            $table->time("start_time")->nullable();
            $table->time("end_time")->nullable();
            $table->string("email")->nullable();
            $table->string("contact")->nullable();
            $table->string("social_link")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
