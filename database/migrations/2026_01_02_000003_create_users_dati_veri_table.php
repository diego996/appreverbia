<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users_dati_veri', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('email')->unique('users_email_unique');
            $table->string('phone', 50)->nullable();
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 50)->default('staff');
            $table->string('status', 50)->default('active');
            $table->rememberToken();
            $table->timestamps();

            $table->index('branch_id', 'users_branch_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_dati_veri');
    }
};
