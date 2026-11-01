<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->foreignId('assigned_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title');
            $table->enum('assignment_type', [
                'class',
                'event',
                'meeting',
                'other',
            ])->default('other');

            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            $table->enum('status', [
                'scheduled',
                'cancelled',
            ])->default('scheduled');

            $table->timestamps();

            $table->index(['room_id', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
    }
};