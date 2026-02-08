<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Tref Stays</title>
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
        body { font-family: 'Inter', sans-serif; background: var(--light); color: var(--text); }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text);
        }

        .page-title { font-size: 1.5rem; font-weight: 700; }

        .conversations-list {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .conversation-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: inherit;
            transition: background 0.2s;
        }

        .conversation-item:last-child { border-bottom: none; }
        .conversation-item:hover { background: var(--light); }
        .conversation-item.unread { background: #eff6ff; }

        .conversation-avatar {
            width: 56px;
            height: 56px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .conversation-content { flex: 1; min-width: 0; }

        .conversation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .conversation-name { font-weight: 600; }
        .conversation-time { font-size: 0.75rem; color: var(--text-muted); }
        .conversation-preview {
            font-size: 0.875rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conversation-property {
            font-size: 0.75rem;
            color: var(--primary);
            margin-top: 0.25rem;
        }

        .unread-badge {
            background: var(--primary);
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: var(--radius);
        }

        .empty-state svg { width: 64px; height: 64px; color: var(--text-muted); margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.125rem; margin-bottom: 0.5rem; }
        .empty-state p { color: var(--text-muted); margin-bottom: 1rem; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            background: var(--primary);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <a href="{{ route('renter.dashboard') }}" class="back-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="page-title">Messages</h1>
        </div>

        @if($conversations->count() > 0)
        <div class="conversations-list">
            @foreach($conversations as $conversation)
            @php
                $otherUser = $conversation->getOtherUser(Auth::id());
                $unread = $conversation->getUnreadCount(Auth::id());
            @endphp
            <a href="{{ route('renter.conversation', $conversation->id) }}" class="conversation-item {{ $unread > 0 ? 'unread' : '' }}">
                <div class="conversation-avatar">
                    {{ strtoupper(substr($otherUser->first_name ?? 'H', 0, 1)) }}
                </div>
                <div class="conversation-content">
                    <div class="conversation-header">
                        <span class="conversation-name">{{ $otherUser->first_name ?? 'Host' }} {{ $otherUser->last_name ?? '' }}</span>
                        <span class="conversation-time">
                            {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : '' }}
                        </span>
                    </div>
                    <div class="conversation-preview">
                        {{ Str::limit($conversation->latestMessage->message ?? 'No messages yet', 60) }}
                    </div>
                    @if($conversation->property)
                    <div class="conversation-property">Re: {{ $conversation->property->title }}</div>
                    @endif
                </div>
                @if($unread > 0)
                <span class="unread-badge">{{ $unread }}</span>
                @endif
            </a>
            @endforeach
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            {{ $conversations->links() }}
        </div>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <h3>No conversations yet</h3>
            <p>Contact a host to start messaging!</p>
            <a href="/" class="btn">Browse Properties</a>
        </div>
        @endif
    </div>
</body>
</html>
