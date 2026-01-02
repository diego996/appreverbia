<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('branch_id');
            $table->string('source', 100);
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('message')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->string('status', 50)->default('new');
            $table->integer('kanban_order')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('branch_id', 'leads_branch_id_idx');
            $table->foreign('branch_id', 'leads_branch_id_fk')
                ->references('id')
                ->on('branches')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::create('logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedInteger('user_id');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('user_id', 'logs_user_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
        Schema::dropIfExists('leads');
    }
};
