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
            $table->string('field_type');
            $table->timestamps();
        });

        Schema::create('digital_monsters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('egg_group_id')->constrained('egg_groups')->onDelete('cascade');
            $table->string('sprite_image_0')->nullable();
            $table->string('element_0')->nullable();
            $table->string('sprite_image_1')->nullable();
            $table->string('element_1')->nullable();
            $table->string('sprite_image_2')->nullable();
            $table->string('element_2')->nullable();
            $table->enum('stage', ['Egg', 'Fresh', 'Child', 'Rookie', 'Champion', 'Ultimate', 'Mega']);
            $table->integer('required_evo_points')->default(0);
            $table->timestamps();
        });

        Schema::create('evolution_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evolves_from')->constrained('digital_monsters')->onDelete('cascade');
            $table->foreignId('route_a')->nullable()->constrained('digital_monsters')->onDelete('cascade');
            $table->foreignId('route_b')->nullable()->constrained('digital_monsters')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_digital_monsters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('digital_monster_id')->constrained()->onDelete('cascade');
            $table->boolean('isMain')->default(false);
            $table->string('name')->nullable();
            $table->enum('type', ['Data', 'Virus', 'Vaccine'])->default('Data');
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
            $table->integer('strength')->default(0);
            $table->integer('agility')->default(0);
            $table->integer('defense')->default(0);
            $table->integer('mind')->default(0);
            $table->integer('hunger')->default(0);
            $table->integer('exercise')->default(0);
            $table->integer('clean')->default(0);
            $table->integer('energy')->default(0);
            $table->integer('maxEnergy')->default(5);
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('trainings')->default(0);
            $table->integer('maxTrainings')->default(5);
            $table->integer('currentEvoPoints')->default(0);
            $table->timestamp('sleepStartedAt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_monsters');
        Schema::dropIfExists('egg_groups');
        Schema::dropIfExists('evolution_routes');
        Schema::dropIfExists('user_digital_monsters');
    }
};
