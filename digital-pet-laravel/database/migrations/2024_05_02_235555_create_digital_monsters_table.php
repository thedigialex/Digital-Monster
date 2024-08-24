<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalMonstersTable extends Migration
{

    public function up()
    {
        Schema::create('egg_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('digital_monsters', function (Blueprint $table) {
            $table->id();
            $table->integer('eggId')->default(0);
            $table->integer('monsterId')->default(0);
            $table->string('spriteSheet');
            $table->string('stage')->nullable();
            $table->integer('minWeight')->default(0);
            $table->integer('maxEnergy')->default(0);
            $table->integer('requiredEvoPoints')->default(0);
            $table->timestamps();
        });

        Schema::create('user_digital_monsters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('digital_monster_id')->constrained()->onDelete('cascade');
            $table->boolean('isMain')->default(false);
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->integer('age')->default(0);
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
            $table->integer('strength')->default(0);
            $table->integer('agility')->default(0);
            $table->integer('defense')->default(0);
            $table->integer('mind')->default(0);
            $table->integer('hunger')->default(0);
            $table->integer('exercise')->default(0);
            $table->integer('clean')->default(0);
            $table->integer('weight')->default(0);
            $table->integer('energy')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('trainings')->default(0);
            $table->integer('care_misses')->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('users_digital_monsters');
        Schema::dropIfExists('digital_monsters');
        Schema::dropIfExists('egg_groups');
    }
}
