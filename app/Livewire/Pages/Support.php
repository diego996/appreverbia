<?php

namespace App\Livewire\Pages;

use App\Models\SupportConversation;
use App\Models\SupportMessage;
use App\Models\SupportAttachment;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.reverbia-shell', ['title' => 'Supporto'])]
class Support extends Component
{
    use WithFileUploads;

    public $messageText = '';
    public $attachments = [];
    public $conversation;
    public $messages = [];

    public function mount()
    {
        $user = auth()->user();

        // Get or create conversation for this user with their branch
        $this->conversation = SupportConversation::firstOrCreate(
            [
                'user_id' => $user->id,
                'branch_id' => $user->branch_id,
            ],
            [
                'title' => 'Supporto - ' . $user->name,
                'avatar' => $user->avatar,
                'unread_count' => 0,
            ]
        );

        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = SupportMessage::where('conversation_id', $this->conversation->id)
            ->with('attachments')
            ->orderBy('sent_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'text' => $message->text,
                    'sender_type' => $message->sender_type,
                    'sent_at' => $message->sent_at,
                    'attachments' => $message->attachments->map(function ($att) {
                        return [
                            'id' => $att->id,
                            'filename' => $att->filename,
                            'mime' => $att->mime,
                            'size' => $att->size,
                            'path' => $att->path,
                            'url' => Storage::disk($att->disk)->url($att->path),
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'messageText' => 'required_without:attachments|string|max:5000',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $user = auth()->user();
        $hasAttachments = !empty($this->attachments);

        // Create the message
        $message = SupportMessage::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => $user->id,
            'branch_id' => $user->branch_id,
            'sender_type' => 'customer',
            'text' => $this->messageText ?: null,
            'has_attachments' => $hasAttachments,
            'sent_at' => now(),
        ]);

        // Handle file attachments
        if ($hasAttachments) {
            foreach ($this->attachments as $file) {
                $path = $file->store('support-attachments', 'public');

                SupportAttachment::create([
                    'message_id' => $message->id,
                    'disk' => 'public',
                    'path' => $path,
                    'filename' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by_type' => 'customer',
                    'uploaded_by_name' => $user->name,
                    'uploaded_at' => now(),
                ]);
            }
        }

        // Update conversation
        $this->conversation->update([
            'last_message_at' => now(),
            'last_read_at_customer' => now(),
        ]);

        // Reset form
        $this->messageText = '';
        $this->attachments = [];

        // Reload messages
        $this->loadMessages();

        // Dispatch event to scroll to bottom
        $this->dispatch('message-sent');
    }

    public function render()
    {
        return view('livewire.pages.support');
    }
}
