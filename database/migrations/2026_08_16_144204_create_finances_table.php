<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['income', 'expense']);

            $table->decimal('amount', 12, 2);

            $table->string('source')->nullable();

            $table->string('description', 500)->nullable();

            $table->string('person')->nullable();

            $table->date('transaction_date');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
