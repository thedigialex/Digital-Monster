<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Attack', 'Background', 'Case', 'Consumable', 'Material']);
            $table->enum('rarity', ['Free', 'Common', 'Uncommon', 'Rare', 'Legendary', 'Mystic']);
            $table->string('effect')->nullable();
            $table->integer('price');
            $table->integer('isAvailable')->default(1);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('isEquipped')->default(0);
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('items');
    }
};
