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
            $table->string('image');
            $table->integer('price');
            $table->string('effect')->nullable();
            $table->integer('available')->default(1);
            $table->integer('max_quantity')->default(1);
            $table->enum('type', ['Attack', 'Background', 'Case', 'Consumable', 'Material']);
            $table->enum('rarity', ['Free', 'Common', 'Uncommon', 'Rare', 'Legendary', 'Mystic']);
            $table->timestamps();
        });

        Schema::create('user_items', function (Blueprint $table) {
            $table->id();
            $table->integer('equipped')->default(0);
            $table->integer('quantity')->default(1);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_item');
        Schema::dropIfExists('item');
    }
};
