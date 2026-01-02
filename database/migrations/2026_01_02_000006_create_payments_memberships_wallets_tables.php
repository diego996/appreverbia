<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('user_id');
            $table->string('payable_type')->nullable();
            $table->unsignedBigInteger('payable_id')->nullable();
            $table->bigInteger('amount');
            $table->char('currency', 3)->default('EUR');
            $table->string('provider_ref')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('item_id')->nullable();

            $table->index('status', 'payments_status_index');
            $table->index(['payable_type', 'payable_id'], 'payments_payable_type_payable_id_index');
            $table->foreign('item_id', 'payments_item_id_foreign')
                ->references('id')
                ->on('items')
                ->nullOnDelete();
            $table->foreign('user_id', 'payments_user_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();
            $table->unsignedBigInteger('payment_id')->nullable();

            $table->index('status', 'memberships_status_index');
            $table->foreign('payment_id', 'memberships_payment_id_foreign')
                ->references('id')
                ->on('payments')
                ->nullOnDelete();
            $table->foreign('user_id', 'memberships_user_id_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->integer('token_delta');
            $table->string('reason', 50);
            $table->string('provider')->nullable();
            $table->integer('token')->default(0);
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['user_id', 'model_type', 'model_id', 'reason'], 'wallets_user_model_reason_unique');
            $table->index(['model_type', 'model_id'], 'wallets_model_type_model_id_index');
            $table->foreign('user_id', 'wallets_user_id_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('payments');
    }
};
