<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('unlock_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->integer('unlock_steps')->default(0);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('message');
            $table->foreignId('item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->integer('unlocked')->default(0);
            $table->integer('steps')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_locations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('locations');
    }
};
