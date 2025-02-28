<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->integer('max_level');
            $table->integer('upgrade_item_id')->nullable();
            $table->enum('stat', ['Strength', 'Agility', 'Defense', 'Mind', 'Cleaning', 'Lighting']);
            $table->timestamps();
        });

        Schema::create('user_equipment', function (Blueprint $table) {
            $table->id();
            $table->integer('level');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_equipment');
        Schema::dropIfExists('equipment');
    }
};
