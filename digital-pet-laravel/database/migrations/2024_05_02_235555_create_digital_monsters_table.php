<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalMonstersTable extends Migration
{
    public function up()
    {
        Schema::create('digital_monsters', function (Blueprint $table) {
            $table->id();
            $table->integer('egg_id')->default(0);
            $table->integer('monster_id')->default(0);
            $table->string('sprite_sheet');
            $table->string('stage')->nullable();
            $table->integer('min_weight')->default(0);
            $table->integer('max_energy')->default(0);
            $table->integer('required_evo_points')->default(0);
            $table->timestamps();
        });

        Schema::create('users_digital_monsters', function (Blueprint $table) {
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
    }
}
