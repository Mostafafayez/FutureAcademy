<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_bundle_teacher', function (Blueprint $table) {

            $table->id();

            $table->foreignId('teacher_bundle_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'teacher_bundle_id',
                'teacher_id'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_bundle_teacher');
    }
};
