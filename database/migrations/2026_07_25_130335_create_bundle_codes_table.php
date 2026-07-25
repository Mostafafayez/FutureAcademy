<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundle_codes', function (Blueprint $table) {

            $table->id();

            $table->string('code')->unique();

            $table->foreignId('teacher_bundle_id')
                ->constrained('teacher_bundles')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('mac_address')->nullable();
            // $table->string('mac_address2')->nullable();

            $table->enum('status', ['notused', 'used'])
                ->default('notused');

            $table->dateTime('expires_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_codes');
    }
};
