<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainer_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('trainer_id', 'workout_plans_trainer_id_idx');
            $table->foreign('trainer_id', 'workout_plans_trainer_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_plans');
    }
};
