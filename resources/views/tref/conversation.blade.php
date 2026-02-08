<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat with {{ $otherUser->first_name ?? 'Host' }} - Tref Stays</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text: #334155;
            --text-muted: #94a3b8;
            --shadow: 0 1px 3px rgba(0,0,0,0.1);
            --radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: var(--light); color: var(--text); display: flex; flex-direction: column; }

        .chat-header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text);
            cursor: pointer;
        }

        .chat-user-info { flex: 1; }
        .chat-user-name { font-weight: 600; font-size: 1.125rem; }
        .chat-property { font-size: 0.875rem; color: var(--primary); }

        .user-avatar {
            width: 44px;
            height: 44px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .message {
            max-width: 70%;
            display: flex;
            flex-direction: column;
        }

        .message.sent { align-self: flex-end; }
        .message.received { align-self: flex-start; }

        .message-bubble {
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            line-height: 1.5;
        }

        .message.sent .message-bubble {
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message.received .message-bubble {
            background: white;
            border: 1px solid var(--border);
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            padding: 0 0.5rem;
        }

        .message.sent .message-time { text-align: right; }

        .date-divider {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.75rem;
            padding: 1rem 0;
        }

        .date-divider span {
            background: var(--light);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }

        .chat-input-container {
            background: white;
            border-top: 1px solid var(--border);
            padding: 1rem 1.5rem;
            position: sticky;
            bottom: 0;
        }

        .chat-input-form {
            display: flex;
            gap: 0.75rem;
            align-items: flex-end;
        }

        .chat-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 24px;
            font-family: inherit;
            font-size: 0.9375rem;
            resize: none;
            min-height: 44px;
            max-height: 120px;
        }

        .chat-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .send-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .send-btn:hover { background: var(--primary-dark); }
        .send-btn:disabled { background: var(--border); cursor: not-allowed; }

        .empty-messages {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-align: center;
            padding: 2rem;
        }

        .empty-messages svg { width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5; }
    </style>
</head>
<body>
    <div class="chat-header">
        <a href="{{ route('renter.messages') }}" class="back-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="user-avatar">{{ strtoupper(substr($otherUser->first_name ?? 'H', 0, 1)) }}</div>
        <div class="chat-user-info">
            <div class="chat-user-name">{{ $otherUser->first_name ?? 'Host' }} {{ $otherUser->last_name ?? '' }}</div>
            @if($conversation->property)
            <div class="chat-property">{{ $conversation->property->title }}</div>
            @endif
        </div>
    </div>

    <div class="messages-container" id="messages">
        @if($messages->count() > 0)
            @php $lastDate = null; @endphp
            @foreach($messages as $message)
                @php
                    $messageDate = $message->created_at->format('Y-m-d');
                    $isSent = $message->sender_id == Auth::id();
                @endphp
                
                @if($lastDate !== $messageDate)
                    <div class="date-divider">
                        <span>{{ $message->created_at->format('M j, Y') }}</span>
                    </div>
                    @php $lastDate = $messageDate; @endphp
                @endif
                
                <div class="message {{ $isSent ? 'sent' : 'received' }}">
                    <div class="message-bubble">{{ $message->message }}</div>
                    <div class="message-time">{{ $message->created_at->format('g:i A') }}</div>
                </div>
            @endforeach
        @else
            <div class="empty-messages">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <p>No messages yet.<br>Start the conversation!</p>
            </div>
        @endif
    </div>

    <div class="chat-input-container">
        <form class="chat-input-form" id="messageForm">
            @csrf
            <input type="hidden" name="recipient_id" value="{{ $otherUser->id }}">
            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
            <input type="hidden" name="property_id" value="{{ $conversation->property_id }}">
            <textarea class="chat-input" name="message" placeholder="Type a message..." rows="1" id="messageInput"></textarea>
            <button type="submit" class="send-btn" id="sendBtn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>

    <script>
        // Scroll to bottom on load
        const messagesContainer = document.getElementById('messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Auto-resize textarea
        const input = document.getElementById('messageInput');
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Handle form submission
        document.getElementById('messageForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = input.value.trim();
            if (!message) return;

            const formData = new FormData(this);
            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;

            try {
                const response = await fetch('{{ route("renter.send-message") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    // Add message to UI
                    const messageHtml = `
                        <div class="message sent">
                            <div class="message-bubble">${message}</div>
                            <div class="message-time">Just now</div>
                        </div>
                    `;
                    messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    
                    // Clear input
                    input.value = '';
                    input.style.height = 'auto';
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }

            sendBtn.disabled = false;
        });

        // Send on Enter (but Shift+Enter for new line)
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('messageForm').dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>
