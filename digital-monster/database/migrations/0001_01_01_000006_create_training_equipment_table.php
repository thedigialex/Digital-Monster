<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('max_level');
            $table->integer('upgrade_item_id')->nullable();
            $table->enum('stat', ['Strength', 'Agility', 'Defense', 'Mind', 'Cleaning']);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('user_training_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_equipment_id')->constrained('training_equipment')->onDelete('cascade');
            $table->integer('level');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_training_equipment');
        Schema::dropIfExists('training_equipment');
    }
};
