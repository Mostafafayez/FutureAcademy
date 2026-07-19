<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_bundles', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('description')->nullable();

            $table->foreignId('educational_level_id')
                  ->constrained('educational_levels')
                  ->cascadeOnDelete();

            $table->decimal('price',10,2)->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_bundles');
    }
};
