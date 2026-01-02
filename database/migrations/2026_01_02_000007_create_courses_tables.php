<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('branch_id');
            $table->unsignedBigInteger('trainer_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamps();

            $table->index('branch_id', 'courses_branch_id_idx');
            $table->index('trainer_id', 'courses_trainer_id_idx');
            $table->foreign('branch_id', 'courses_branch_id_fk')
                ->references('id')
                ->on('branches')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('trainer_id', 'courses_trainer_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::create('course_occurrences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('max_participants')->default(0);
            $table->timestamps();

            $table->index('course_id', 'course_occurrences_course_id_idx');
            $table->foreign('course_id', 'course_occurrences_course_id_fk')
                ->references('id')
                ->on('courses')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::create('course_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('occurrence_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('booked_at')->nullable();
            $table->string('status', 50)->default('booked');
            $table->timestamps();

            $table->unique(['occurrence_id', 'user_id'], 'course_bookings_unique');
            $table->unique(['user_id', 'occurrence_id'], 'course_bookings_unique1');
            $table->index('user_id', 'course_bookings_customer_id_idx');
            $table->foreign('occurrence_id', 'course_bookings_occurrence_id_fk')
                ->references('id')
                ->on('course_occurrences')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('user_id', 'course_bookings_user_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        Schema::create('course_waitlist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('occurrence_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('added_at')->nullable();
            $table->string('status', 50)->default('waiting');
            $table->timestamps();

            $table->unique(['occurrence_id', 'user_id'], 'course_waitlist_unique');
            $table->index('user_id', 'course_waitlist_customer_id_idx');
            $table->foreign('occurrence_id', 'course_waitlist_occurrence_id_fk')
                ->references('id')
                ->on('course_occurrences')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('user_id', 'course_waitlist_user_id_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_waitlist');
        Schema::dropIfExists('course_bookings');
        Schema::dropIfExists('course_occurrences');
        Schema::dropIfExists('courses');
    }
};
