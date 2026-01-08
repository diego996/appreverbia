<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->string('title');
            $table->string('assigned_to')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedInteger('unread_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamp('last_read_at_staff')->nullable();
            $table->timestamp('last_read_at_customer')->nullable();
            $table->timestamps();

            $table->index('last_message_at', 'support_conversations_last_message_at_index');
            $table->index(['branch_id', 'last_message_at'], 'sc_branch_lastmsg_idx');
            $table->foreign('branch_id', 'sc_branch_fk')
                ->references('id')
                ->on('branches')
                ->nullOnDelete();
            $table->foreign('user_id', 'sc_user_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->enum('sender_type', ['customer', 'staff']);
            $table->text('text')->nullable();
            $table->boolean('has_attachments')->default(false);
            $table->timestamp('sent_at');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'sent_at'], 'support_messages_conversation_id_sent_at_index');
            $table->index('sender_type', 'support_messages_sender_type_index');
            $table->index(['conversation_id', 'sent_at'], 'sm_conv_sent_idx');
            $table->index(['branch_id', 'sent_at'], 'sm_branch_sent_idx');
            $table->foreign('branch_id', 'sm_branch_fk')
                ->references('id')
                ->on('branches')
                ->nullOnDelete();
            $table->foreign('user_id', 'sm_user_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->foreign('conversation_id', 'support_messages_conversation_id_foreign')
                ->references('id')
                ->on('support_conversations')
                ->cascadeOnDelete();
        });

        Schema::create('support_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('message_id');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('filename');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->enum('uploaded_by_type', ['customer', 'staff']);
            $table->string('uploaded_by_name');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

            $table->index('uploaded_by_type', 'support_attachments_uploaded_by_type_index');
            $table->foreign('message_id', 'support_attachments_message_id_foreign')
                ->references('id')
                ->on('support_messages')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_attachments');
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_conversations');
    }
};
