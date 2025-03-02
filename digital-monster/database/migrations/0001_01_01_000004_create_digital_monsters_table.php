<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('egg_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('field');
            $table->timestamps();
        });

        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('egg_group_id')->constrained()->onDelete('cascade');
            $table->string('image_0');
            $table->string('element_0');
            $table->string('image_1')->nullable();
            $table->string('element_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('element_2')->nullable();
            $table->enum('stage', ['Egg', 'Fresh', 'Child', 'Rookie', 'Champion', 'Ultimate', 'Mega']);
            $table->integer('evo_requirement')->default(0);
            $table->timestamps();
        });

        Schema::create('evolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_monster')->constrained('monsters')->onDelete('cascade');
            $table->foreignId('route_0')->nullable()->constrained('monsters')->onDelete('cascade');
            $table->foreignId('route_1')->nullable()->constrained('monsters')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_monsters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('monster_id')->constrained()->onDelete('cascade');
            $table->integer('main')->default(0);
            $table->string('name')->nullable();
            $table->enum('type', ['Data', 'Virus', 'Vaccine'])->default('Data');
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
            $table->integer('attack')->default(0);
            $table->integer('colosseum')->default(1);
            $table->integer('strength')->default(0);
            $table->integer('agility')->default(0);
            $table->integer('defense')->default(0);
            $table->integer('mind')->default(0);
            $table->integer('hunger')->default(0);
            $table->integer('exercise')->default(0);
            $table->integer('clean')->default(0);
            $table->integer('energy')->default(0);
            $table->integer('max_energy')->default(5);
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('trainings')->default(0);
            $table->integer('max_trainings')->default(5);
            $table->integer('evo_points')->default(0);
            $table->timestamp('sleep_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monsters');
        Schema::dropIfExists('egg_groups');
        Schema::dropIfExists('evolutions');
        Schema::dropIfExists('user_monsters');
    }
};
