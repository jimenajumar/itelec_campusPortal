<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_id')
                ->constrained('buildings')
                ->cascadeOnDelete();

            $table->string('room_number');
            $table->unsignedInteger('capacity')->default(0);
            $table->string('room_type')->default('classroom');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['building_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};