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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('educational_level_id');
            // $table->unsignedBigInteger('subject_id');
            // $table->timestamps(0); // This line is optional; set to false if you don't want timestamps
            $table->foreign('educational_level_id')->references('id')->on('educational_levels')->onDelete('cascade'); // Assuming 'educational_levels' table exists
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
