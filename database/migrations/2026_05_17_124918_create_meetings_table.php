<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('daily_room_name')->unique();

            $table->text('daily_room_url');

            $table->text('meeting_token')->nullable();

            $table->enum('status', [
                'waiting',
                'started',
                'ended',
                'cancelled'
            ])->default('waiting');

            $table->timestamp('started_at')->nullable();

            $table->timestamp('ended_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

