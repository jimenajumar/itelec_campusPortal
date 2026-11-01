<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add the new room information columns.
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('room_name')->nullable();
            $table->string('location')->nullable();
        });

        // Remove the relationship to the buildings table.
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['building_id']);
            $table->dropUnique(['building_id', 'room_number']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('building_id');
        });

        // Room numbers must be unique across campus.
        Schema::table('rooms', function (Blueprint $table) {
            $table->unique('room_number');
        });

        // Remove the unused buildings table.
        Schema::dropIfExists('buildings');
    }

    public function down(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropUnique(['room_number']);
            $table->foreignId('building_id')->nullable();
            $table->dropColumn(['room_name', 'location']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->cascadeOnDelete();

            $table->unique(['building_id', 'room_number']);
        });
    }
};
