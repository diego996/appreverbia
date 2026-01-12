@push('styles')
    <style>
        main.page-support {
            padding: 0;
            height: calc(100vh - 60px - 70px);
            /* viewport - topbar - bottom nav */
            display: flex;
            flex-direction: column;
            background: var(--bg);
        }

        /* Chat Header */
        .chat-header {
            padding: 16px 18px;
            background: linear-gradient(180deg, rgba(126, 252, 91, 0.08), rgba(5, 5, 5, 0.94));
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-header-icon {
            width: 42px;
            height: 42px;
            background: rgba(126, 252, 91, 0.15);
            border: 1px solid rgba(126, 252, 91, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--accent);
        }

        .chat-header-info h2 {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 2px 0;
        }

        .chat-header-info p {
            font-size: 12px;
            color: var(--muted);
            margin: 0;
        }

        /* Messages Container */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .messages-container::-webkit-scrollbar-track {
            background: var(--panel);
        }

        .messages-container::-webkit-scrollbar-thumb {
            background: var(--line);
            border-radius: 3px;
        }

        .messages-container::-webkit-scrollbar-thumb:hover {
            background: var(--muted);
        }

        /* Message Bubble */
        .message-bubble {
            max-width: 85%;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .message-bubble.customer {
            align-self: flex-end;
            align-items: flex-end;
        }

        .message-bubble.staff {
            align-self: flex-start;
            align-items: flex-start;
        }

        .message-content {
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            font-size: 15px;
            line-height: 1.4;
        }

        .message-bubble.customer .message-content {
            background: linear-gradient(135deg, var(--accent) 0%, #8fff6b 100%);
            color: #000;
            border-bottom-right-radius: 4px;
        }

        .message-bubble.staff .message-content {
            background: var(--panel-2);
            color: var(--text);
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 11px;
            color: var(--muted);
            padding: 0 8px;
        }

        /* Attachments */
        .message-attachments {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 4px;
        }

        .attachment-item {
            background: rgba(126, 252, 91, 0.1);
            border: 1px solid rgba(126, 252, 91, 0.3);
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text);
            transition: all 0.2s ease;
            max-width: 280px;
        }

        .attachment-item:hover {
            background: rgba(126, 252, 91, 0.15);
            border-color: rgba(126, 252, 91, 0.5);
        }

        .attachment-icon {
            width: 36px;
            height: 36px;
            background: rgba(126, 252, 91, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--accent);
            flex-shrink: 0;
        }

        .attachment-info {
            flex: 1;
            min-width: 0;
        }

        .attachment-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .attachment-size {
            font-size: 11px;
            color: var(--muted);
        }

        /* Empty State */
        .empty-messages {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
            color: var(--muted);
        }

        .empty-messages i {
            font-size: 64px;
            color: var(--accent);
            opacity: 0.3;
            margin-bottom: 16px;
        }

        .empty-messages h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text);
        }

        .empty-messages p {
            font-size: 14px;
        }

        /* Input Area */
        .input-area {
            padding: 12px 16px;
            background: var(--panel);
            border-top: 1px solid var(--line);
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .input-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-field {
            background: var(--panel-2);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 12px 16px;
            color: var(--text);
            font-size: 15px;
            font-family: 'Montserrat', system-ui, -apple-system, sans-serif;
            resize: none;
            max-height: 120px;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: rgba(126, 252, 91, 0.5);
            background: var(--bg);
        }

        .input-field::placeholder {
            color: var(--muted);
        }

        /* Attachment Preview */
        .attachment-preview {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .preview-item {
            background: rgba(126, 252, 91, 0.1);
            border: 1px solid rgba(126, 252, 91, 0.3);
            border-radius: 10px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
        }

        .preview-item i {
            color: var(--accent);
        }

        .preview-remove {
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 0;
            margin-left: 4px;
            transition: color 0.2s ease;
        }

        .preview-remove:hover {
            color: #fc5b5b;
        }

        /* Buttons */
        .btn-attach {
            width: 44px;
            height: 44px;
            background: var(--panel-2);
            border: 1px solid var(--line);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .btn-attach:hover {
            background: var(--accent);
            color: #000;
            border-color: var(--accent);
        }

        .btn-send {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--accent) 0%, #8fff6b 100%);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .btn-send:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(126, 252, 91, 0.3);
        }

        .btn-send:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-send:disabled:hover {
            transform: none;
            box-shadow: none;
        }
    </style>
@endpush

<div>
    <main class="page-support">
        {{-- Chat Header --}}
        <div class="chat-header">
            <div class="chat-header-icon">
                <i class="bi bi-headset"></i>
            </div>
            <div class="chat-header-info">
                <h2>Supporto</h2>
                <p>{{ auth()->user()->branch->nome ?? 'Assistenza' }}</p>
            </div>
        </div>

        {{-- Messages Container --}}
        <div class="messages-container" id="messagesContainer">
            @if(empty($messages))
                <div class="empty-messages">
                    <i class="bi bi-chat-dots"></i>
                    <h3>Nessun messaggio</h3>
                    <p>Inizia una conversazione con il nostro team di supporto</p>
                </div>
            @else
                @foreach($messages as $message)
                    <div class="message-bubble {{ $message['sender_type'] }}">
                        @if($message['text'])
                            <div class="message-content">{{ $message['text'] }}</div>
                        @endif

                        @if(!empty($message['attachments']))
                            <div class="message-attachments">
                                @foreach($message['attachments'] as $attachment)
                                    <a href="{{ $attachment['url'] }}" target="_blank" class="attachment-item">
                                        <div class="attachment-icon">
                                            @if(str_starts_with($attachment['mime'], 'image/'))
                                                <i class="bi bi-file-image"></i>
                                            @elseif(str_starts_with($attachment['mime'], 'application/pdf'))
                                                <i class="bi bi-file-pdf"></i>
                                            @else
                                                <i class="bi bi-file-earmark"></i>
                                            @endif
                                        </div>
                                        <div class="attachment-info">
                                            <div class="attachment-name">{{ $attachment['filename'] }}</div>
                                            <div class="attachment-size">{{ number_format($attachment['size'] / 1024, 1) }} KB</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <div class="message-time">
                            {{ $message['sent_at']->format('H:i') }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Input Area --}}
        <div class="input-area">
            <label for="fileInput" class="btn-attach">
                <i class="bi bi-paperclip"></i>
            </label>
            <input type="file" id="fileInput" wire:model="attachments" multiple style="display: none;"
                accept="image/*,.pdf,.doc,.docx">

            <div class="input-wrapper">
                @if(!empty($attachments))
                    <div class="attachment-preview">
                        @foreach($attachments as $index => $file)
                            <div class="preview-item">
                                <i class="bi bi-file-earmark"></i>
                                <span>{{ $file->getClientOriginalName() }}</span>
                                <button type="button" class="preview-remove"
                                    wire:click="$set('attachments.{{ $index }}', null)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <textarea wire:model="messageText" wire:keydown.enter.prevent="sendMessage" class="input-field"
                    placeholder="Scrivi messaggio..." rows="1"></textarea>
            </div>

            <button type="button" class="btn-send" wire:click="sendMessage" wire:loading.attr="disabled"
                @disabled(empty($messageText) && empty($attachments))>
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </main>
</div>

@push('scripts')
    <script>
        (function () {
            function scrollToBottom() {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }

            // Scroll on initial load
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(scrollToBottom, 100);
            });

            // Scroll on Livewire navigation
            document.addEventListener('livewire:navigated', () => {
                setTimeout(scrollToBottom, 100);
            });

            // Scroll when new message is sent
            window.addEventListener('message-sent', () => {
                setTimeout(scrollToBottom, 100);
            });

            // Auto-resize textarea
            const textarea = document.querySelector('.input-field');
            if (textarea) {
                textarea.addEventListener('input', function () {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                });
            }
        })();
    </script>
@endpush