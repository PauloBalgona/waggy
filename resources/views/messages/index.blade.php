@extends('navbar.nav2')
@section('title', 'Messages - Waggy')
@section('content')

<style>
.main-wrapper {
    display: flex;
    position: relative;
    left: 8px;
    top: 5px;
    max-width: 1200px;
    height: 500px;
    margin: 2rem auto;
    border: 1px solid #3b3f50;
    border-radius: 12px;
    overflow: hidden;
    background-color: #1a1f2e;
    box-shadow: 0 8px 20px rgba(0,0,0,0.4);
}

body {
    margin: 0;
    padding: 0;
}

.messaging-container {
    display: flex;
    width: 100%;
    min-height: 300px;
    height: auto;
}

/* SIDEBAR */
.conversations-sidebar {
    width: 360px;
    background-color: #1c2230;
    border-right: 1px solid #3b3f50;
    display: flex;
    flex-direction: column;
}

.conversation-header {
    padding: 1rem;
    border-bottom: 1px solid #2d3748;
}

.conversation-search {
    width: 100%;
    margin-top: .75rem;
    padding: .5rem;
    background: #2a3142;
    border: none;
    border-radius: 6px;
    color: white;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
}

.conversation-item {
    display: flex;
    gap: .75rem;
    padding: .75rem 1rem;
    border-bottom: 1px solid #2d3748;
    cursor: pointer;
    text-decoration: none;
}

.conversation-item:hover {
    background: #2a3142;
}

/* CHAT AREA */
.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #1a1f2e;
}

.chat-header {
    padding: 1rem;
    background-color: #1c2230;
    border-bottom: 1px solid #2d3748;
    display: flex;
    align-items: center;
    gap: .75rem;
}

.back-btn {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    @if(isset($user))
        display: block;
    @else
        display: none;
    @endif
}

.messages-area {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
}

.message-received,
.message-sent {
    margin-bottom: .75rem;
}

.message-received {
    display: flex;
    gap: .5rem;
}

.message-sent {
    display: flex;
    justify-content: flex-end;
}

.message-bubble-received {
    background: #2a3142;
    padding: .75rem 1rem;
    border-radius: 1rem;
    color: white;
    max-width: 70%;
}

.message-bubble-sent {
    background: #3b82f6;
    padding: .75rem 1rem;
    border-radius: 1rem;
    color: white;
    max-width: 70%;
}

.message-input-area {
    padding: 1rem;
    background: #1c2230;
    border-top: 1px solid #2d3748;
}

.message-form {
    display: flex;
    gap: .5rem;
}

.message-input {
    flex: 1;
    padding: .75rem 1rem;
    border-radius: 999px;
    border: none;
    background: #2a3142;
    color: white;
}

.send-button {
    background: #3b82f6;
    color: white;
    border: none;
    padding: .75rem 1.25rem;
    border-radius: 999px;
}

/* MOBILE */
@media (max-width: 768px) {
    .messaging-container {
        flex-direction: column;
    }
    
    .conversations-sidebar {
        width: 100%;
        @if(isset($user))
            display: none;
        @endif
    }
    
    .chat-area {
        width: 100%;
    }
    
    .message-input-area {
        padding: 0.5rem;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100vw;
        z-index: 100;
        box-sizing: border-box;
    }
    .messages-area {
        padding-bottom: 140px !important;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    
    .message-form {
        display: flex;
        gap: 0.5rem;
    }
    
    .message-input {
        flex: 1;
        padding: 0.5rem;
        border-radius: 20px;
        border: none;
        background: #2a3142;
        color: white;
        font-size: 14px;
    }
    
    .send-button {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 14px;
    }
}

*::-webkit-scrollbar {
    display: none;
}
</style>

<div class="messaging-container">
    <!-- LEFT SIDEBAR -->
    <div class="conversations-sidebar">
        <div class="conversation-header">
            <h2 style="color:white;margin:0;">Messages</h2>
            <input type="text" class="conversation-search" id="searchInput" placeholder="Search..." onkeyup="searchConversations()">
        </div>
        
        <div class="conversations-list">
            <h6 style="color:white;padding:0.75rem;">Conversations</h6>
            @forelse($conversations ?? [] as $conversation)
                <a href="{{ route('messages.conversation', $conversation->other_user->id) }}" 
                   class="conversation-item" 
                   data-name="{{ $conversation->other_user->pet_name ?? '' }}" 
                   data-breed="{{ $conversation->other_user->pet_breed ?? '' }}">
                    <img src="{{ $conversation->other_user->avatar ? asset('storage/'.$conversation->other_user->avatar) : asset('assets/usericon.png') }}" 
                         style="width:40px;height:40px;border-radius:50%;">
                    <div>
                        <p style="color:white;margin:0;">{{ $conversation->other_user->pet_name }}</p>
                        <small style="color:#8b95a5;">
                            @if($conversation->last_message)
                                @if($conversation->last_message->sender_id == auth()->id())
                                    You: {{ Str::limit($conversation->last_message->message, 30) }}
                                @else
                                    {{ $conversation->other_user->pet_name }}: {{ Str::limit($conversation->last_message->message, 30) }}
                                @endif
                            @else
                                Sent a message
                            @endif
                        </small>
                    </div>
                </a>
            @empty
                <p style="color:#8b95a5;text-align:center;">No conversations</p>
            @endforelse
        </div>
    </div>

    <!-- CHAT AREA -->
    <div class="chat-area">
        <div class="chat-header">
            @if(isset($user))
                <button class="back-btn" onclick="window.location.href='{{ route('messages') }}'">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('assets/usericon.png') }}" 
                     style="width:45px;height:45px;border-radius:50%;">
                <div>
                    <h5 style="color:white;margin:0;">{{ $user->pet_name }}</h5>
                    <small style="color:#8b95a5;">{{ $user->pet_breed }}</small>
                </div>
            @endif
        </div>

        <div class="messages-area" id="messagesArea">
            @if(isset($messages) && $messages->count())
                @foreach($messages as $message)
                    @if($message->sender_id == auth()->id())
                        <div class="message-sent" data-message-id="{{ $message->id }}">
                            <div class="message-bubble-sent">
                                <p style="margin: 0; color: white;">{{ $message->message }}</p>
                                <small style="color: rgba(255,255,255,0.7); font-size: 0.75rem;">{{ $message->created_at->format('h:i A') }}</small>
                            </div>
                        </div>
                    @else
                        <div class="message-received" data-message-id="{{ $message->id }}">
                            <img src="{{ $message->sender->avatar ? asset('storage/'.$message->sender->avatar) : asset('assets/usericon.png') }}" 
                                 style="width:32px;height:32px;border-radius:50%;">
                            <div class="message-bubble-received">
                                <p style="margin: 0; color: white;">{{ $message->message }}</p>
                                <small style="color: #8b95a5; font-size: 0.75rem;">{{ $message->created_at->format('H:i') }}</small>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <p style="color:#8b95a5;text-align:center;">No messages yet</p>
            @endif
        </div>

        <div class="message-input-area">
            @if(isset($user))
                <form method="POST" action="{{ route('messages.send') }}" class="message-form" id="messageForm">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    <input type="text" name="message" class="message-input" placeholder="Type a message..." required id="messageInput">
                    <button type="submit" class="send-button">Send</button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
function searchConversations() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const conversationItems = document.querySelectorAll('.conversation-item');
    
    conversationItems.forEach(item => {
        const name = (item.getAttribute('data-name') || '').toLowerCase();
        const breed = (item.getAttribute('data-breed') || '').toLowerCase();
        
        if (searchInput === '' || name.includes(searchInput) || breed.includes(searchInput)) {
            item.style.setProperty('display', 'flex', 'important');
        } else {
            item.style.setProperty('display', 'none', 'important');
        }
    });
}

// Handle message form submission via AJAX
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('messageForm');

    // Auto-start polling for new messages (receiver side only)
    const receiverIdInput = document.querySelector('input[name="receiver_id"]');
    if (receiverIdInput) {
        startPolling(receiverIdInput.value);
    }

    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageInput = document.getElementById('messageInput');
            const messageText = messageInput.value.trim();
            
            if (!messageText) return;
            
            // Optimistically add the message to the chat area immediately
            const messagesArea = document.getElementById('messagesArea');
            const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
            
            const optimisticHtml = `
                <div class="message-sent optimistic-message">
                    <div class="message-bubble-sent">
                        <p style="margin: 0; color: white;">${escapeHtml(messageText)}</p>
                        <small style="color: rgba(255,255,255,0.7); font-size: 0.75rem;">${now}</small>
                    </div>
                </div>
            `;
            
            messagesArea.insertAdjacentHTML('beforeend', optimisticHtml);
            messagesArea.scrollTop = messagesArea.scrollHeight;
            
            // Clear the input immediately
            messageInput.value = '';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Remove the optimistic message
                const optimistic = messagesArea.querySelector('.optimistic-message');
                if (optimistic) optimistic.remove();

                if (data.success) {
                    // Add the confirmed message from server with data-message-id
                    const confirmedHtml = `
                        <div class="message-sent" data-message-id="${data.message.id}">
                            <div class="message-bubble-sent">
                                <p style="margin: 0; color: white;">${escapeHtml(data.message.message)}</p>
                                <small style="color: rgba(255,255,255,0.7); font-size: 0.75rem;">${now}</small>
                            </div>
                        </div>
                    `;

                    messagesArea.insertAdjacentHTML('beforeend', confirmedHtml);
                    messagesArea.scrollTop = messagesArea.scrollHeight;

                    // Update the conversation list to show "You: [message]"
                    const conversationsList = document.querySelector('.conversations-list');
                    if (conversationsList) {
                        const receiverId = document.querySelector('input[name="receiver_id"]').value;
                        const convItem = conversationsList.querySelector(`a[href*="/messages/${receiverId}"]`);

                        if (convItem) {
                            const small = convItem.querySelector('small');
                            if (small) {
                                const displayText = data.message.message.length > 30 
                                    ? data.message.message.substring(0, 30) + '...' 
                                    : data.message.message;
                                small.textContent = 'You: ' + displayText;
                            }

                            // Move to top of conversations
                            const firstConv = conversationsList.querySelector('.conversation-item');
                            if (firstConv && convItem !== firstConv) {
                                conversationsList.insertBefore(convItem, firstConv);
                            }
                        }
                    }
                } else {
                    alert('Error sending message: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                // Remove the optimistic message if error
                const optimistic = messagesArea.querySelector('.optimistic-message');
                if (optimistic) optimistic.remove();
                
                console.error('Error:', error);
                alert('Error sending message. Please try again.');
            });
        });
    }
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Auto-scroll to bottom on page load
window.addEventListener('DOMContentLoaded', function () {
    const messagesArea = document.getElementById('messagesArea');
    if (messagesArea && !messagesArea.querySelector('[style*="justify-content: center"]')) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
});

// Polling fallback for realtime messages
let pollInterval = null;
let lastMessageId = 0;

function updateLastMessageIdFromDOM() {
    const messagesArea = document.getElementById('messagesArea');
    if (!messagesArea) return;
    
    const ids = Array.from(messagesArea.querySelectorAll('[data-message-id]'))
        .map(el => parseInt(el.getAttribute('data-message-id') || '0'));
    lastMessageId = ids.length ? Math.max(...ids) : 0;
}

function startPolling(userId) {
    stopPolling();
    updateLastMessageIdFromDOM();
    
    if (!userId) return;
    
    pollInterval = setInterval(async () => {
        try {
            const resp = await fetch(`/api/messages/${userId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (!resp.ok) return;
            
            const json = await resp.json();
            if (!json.messages) return;
            
            const messagesArea = document.getElementById('messagesArea');
            let appended = false;
            
            json.messages.forEach(msg => {
                if ((msg.id || 0) > lastMessageId) {
                    appended = true;
                    const createdAt = msg.created_at || '';
                    
                    if (msg.sender_id == userId) {
                        const messageHtml = `
                            <div class="message-received" data-message-id="${msg.id}">
                                <img src="${msg.sender.avatar || '/assets/usericon.png'}" 
                                     style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;">
                                <div class="message-bubble-received">
                                    <p style="margin: 0; color: white;">${escapeHtml(msg.message || '')}</p>
                                    <small style="color: #8b95a5; font-size: 0.75rem;">${createdAt}</small>
                                </div>
                            </div>
                        `;
                        messagesArea.insertAdjacentHTML('beforeend', messageHtml);
                    } else {
                        const now = (new Date()).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
                        const messageHtml = `
                            <div class="message-sent" data-message-id="${msg.id}">
                                <div class="message-bubble-sent">
                                    <p style="margin: 0; color: white;">${escapeHtml(msg.message || '')}</p>
                                    <small style="color: rgba(255,255,255,0.7); font-size: 0.75rem;">${now}</small>
                                </div>
                            </div>
                        `;
                        messagesArea.insertAdjacentHTML('beforeend', messageHtml);
                    }
                    
                    lastMessageId = Math.max(lastMessageId, msg.id || 0);
                }
            });
            
            if (appended) messagesArea.scrollTop = messagesArea.scrollHeight;
        } catch (err) {
            console.debug('Polling messages failed', err);
        }
    }, 3000);
}

function stopPolling() {
    if (pollInterval) {
        clearInterval(pollInterval);
        pollInterval = null;
    }
}

// Delete conversation function
function deleteConversation(event, userId) {
    event.preventDefault();
    event.stopPropagation();
    
    if (confirm('Are you sure you want to delete this conversation? All messages will be removed.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/conversations/${userId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Toggle conversation menu
function toggleConversationMenu(event, userId) {
    event.preventDefault();
    event.stopPropagation();
    
    const menu = document.getElementById(`conv-menu-${userId}`);
    
    // Close all other menus
    document.querySelectorAll('.conversation-dropdown.show').forEach(dropdown => {
        if (dropdown.id !== `conv-menu-${userId}`) {
            dropdown.classList.remove('show');
        }
    });
    
    menu.classList.toggle('show');
}

// Block user function
function blockUser(userId) {
    if (confirm('Are you sure you want to block this user? You will no longer see their posts or messages.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/posts/${userId}/block`;
        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

// Unblock user function
function unblockUser(userId) {
    if (confirm('Are you sure you want to unblock this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/posts/${userId}/unblock`;
        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

// Close conversation dropdown when clicking outside
document.addEventListener('click', function (event) {
    if (!event.target.closest('.conversation-menu')) {
        document.querySelectorAll('.conversation-dropdown.show').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});
</script>

<script>
// Attach message listeners when Echo is available
(function () {
    const userId = {{ auth()->id() ?? 'null' }};
    if (!userId) return;
    
    const attachListeners = () => {
        try {
            const messagesArea = document.getElementById('messagesArea');
            const conversationsList = document.getElementById('conversationsList');
            
            if (typeof window.Echo === 'undefined' || !window.Echo.private) {
                setTimeout(attachListeners, 200);
                return;
            }
            
            window.Echo.private(`user.${userId}`)
                .listen('MessageSent', (e) => {
                    try {
                        const senderId = e.sender_id || e.message?.sender_id || e.sender?.id;
                        const messageText = e.message || (e.message && e.message.message) || e.message_text || '';
                        const createdAt = e.created_at || (new Date()).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        
                        const convItem = document.querySelector(`.conversation-item[data-user-id="${senderId}"]`);
                        
                        if (convItem) {
                            const headerSmall = convItem.querySelector('div > div > small');
                            if (headerSmall) headerSmall.textContent = 'now';
                            
                            const smalls = convItem.querySelectorAll('small');
                            if (smalls.length > 0) {
                                const displayText = messageText 
                                    ? (messageText.length > 30 ? messageText.substring(0, 30) + '...' : messageText)
                                    : 'Sent a message';
                                smalls[smalls.length - 1].textContent = senderId == {{ auth()->id() }} 
                                    ? 'You: ' + displayText 
                                    : '{{ auth()->user()->pet_name }}: ' + displayText;
                            }
                            
                            const firstConv = conversationsList.querySelector('.conversation-item');
                            if (firstConv && convItem !== firstConv) {
                                conversationsList.insertBefore(convItem, firstConv);
                            }
                        }
                        
                        const receiverInput = document.getElementById('receiverId');
                        if (receiverInput && parseInt(receiverInput.value) === parseInt(senderId)) {
                            if (messagesArea) {
                                const messageHtml = `
                                    <div class="message-received">
                                        <img src="${e.sender?.avatar || '/assets/usericon.png'}" 
                                             style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;">
                                        <div class="message-bubble-received">
                                            <p style="margin: 0; color: white;">${escapeHtml(messageText)}</p>
                                            <small style="color: #8b95a5; font-size: 0.75rem;">${createdAt}</small>
                                        </div>
                                    </div>
                                `;
                                messagesArea.insertAdjacentHTML('beforeend', messageHtml);
                                messagesArea.scrollTop = messagesArea.scrollHeight;
                            }
                        } else {
                            if (convItem) {
                                convItem.style.backgroundColor = '#233043';
                                setTimeout(() => convItem.style.backgroundColor = '', 3000);
                            }
                            
                            const messagesBadge = document.querySelector('a[href="{{ route('messages') }}"] .notification-badge');
                            if (messagesBadge) {
                                const val = parseInt(messagesBadge.textContent || '0') || 0;
                                messagesBadge.textContent = val + 1;
                            }
                            
                            try {
                                const sender = e.sender || {};
                                window.showToast({
                                    title: sender.pet_name || 'New message',
                                    body: (e.message || '').length > 80 
                                        ? (e.message || '').substring(0, 80) + '...' 
                                        : (e.message || ''),
                                    icon: sender.avatar || null,
                                    timeout: 6000,
                                    click: function () {
                                        window.location.href = '{{ route('messages') }}';
                                    }
                                });
                            } catch (err) {
                                console.debug('toast show failed', err);
                            }
                        }
                    } catch (err) {
                        console.error('Error handling realtime message:', err);
                    }
                });
        } catch (err) {
            console.warn('Realtime init failed on messages page:', err);
        }
    };
    
    attachListeners();
})();
</script>

@endsection