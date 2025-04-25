@extends('layouts.app')

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-chat">
                <div class="nk-chat-aside">
                    <div class="nk-chat-aside-head">
                        <div class="nk-chat-aside-user">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle">
                                    <div class="user-avatar">
                                        <span class="avatar-text">
                                            {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                                        </span>
                                        {{-- <img src="{{ $user->avatar ?? '/demo6/images/avatar/b-sm.jpg' }}" alt=""> --}}
                                    </div>
                                    <div class="title">Chats</div>
                                </a>

                            </div>
                        </div>
                        <ul class="nk-chat-aside-tools g-2">
                            <li>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-round btn-icon btn-light dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <em class="icon ni ni-setting-alt-fill"></em>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            {{-- <li><a href="#"><span>Settings</span></a></li>
                                            <li class="divider"></li>
                                            <li><a href="#"><span>Message Requests</span></a></li>
                                            <li><a href="#"><span>Archived Chats</span></a></li>
                                            <li><a href="#"><span>Unread Chats</span></a></li> --}}
                                            <li><a href="#" onclick="showCreateGroupModal()"><span>Create
                                                        Group</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            {{-- <li>
                                <a href="#" class="btn btn-round btn-icon btn-light">
                                    <em class="icon ni ni-edit-alt-fill"></em>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="nk-chat-aside-body simplebar-scrollable-y" data-simplebar="init">
                        <div class="nk-chat-aside-search">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-search"></em>
                                    </div>
                                    <input type="text" class="form-control form-round" id="chat-search"
                                        placeholder="Search by name">
                                </div>
                            </div>
                        </div>
                        <div class="nk-chat-list">
                            <h6 class="title overline-title-alt">Messages</h6>
                            <div class="mb-3">
                                <button class="btn btn-primary btn-sm" onclick="showDirectChatModal()">
                                    <em class="icon ni ni-plus"></em> Start New Chat
                                </button>
                            </div>
                            <ul class="chat-list">
                                @foreach ($conversations as $conversation)
                                    <li class="chat-item {{ $conversation->unread_count > 0 ? 'is-unread' : '' }}">
                                        <a class="chat-link chat-open" href="#"
                                            onclick="loadConversation({{ $conversation->id }})">
                                            <div class="chat-media">
                                                @if ($conversation->isGroup())
                                                    <div class="user-avatar user-avatar-multiple">
                                                        @foreach ($conversation->participants->take(2) as $participant)
                                                            <div class="user-avatar">
                                                                <span class="avatar-text">
                                                                    {{ strtoupper(substr($participant->name ?? 'U', 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    @php
                                                        $otherParticipant = $conversation->participants
                                                            ->where('user_id', '!=', auth()->id())
                                                            ->first();
                                                    @endphp
                                                    <div class="user-avatar">
                                                        <span class="avatar-text">
                                                            {{ strtoupper(substr($otherParticipant?->name ?? 'U', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">
                                                        @if ($conversation->isGroup())
                                                            {{ $conversation->name ?? 'Unnamed Group' }}
                                                        @else
                                                            {{ $otherParticipant?->name ?? 'Unknown User' }}
                                                        @endif
                                                    </div>
                                                    <span
                                                        class="time">{{ $conversation->lastMessage?->created_at?->diffForHumans() ?? '' }}</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">
                                                        <p>{{ $conversation->lastMessage?->message ?? 'No messages yet' }}
                                                        </p>
                                                    </div>
                                                    @if ($conversation->unread_count > 0)
                                                        <div class="status unread">
                                                            <em class="icon ni ni-bullet-fill"></em>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                        <div class="chat-actions">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-icon btn-sm btn-trigger dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    <em class="icon ni ni-more-h"></em>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li>
                                                            <a href="#" onclick="toggleMute({{ $conversation->id }})">
                                                                {{ $conversation->is_muted ? 'Unmute' : 'Mute' }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="nk-chat-body" id="chat-area" style="display: none;">
                    <div class="nk-chat-head">
                        <ul class="nk-chat-head-info">
                            <li class="nk-chat-body-close">
                                <a href="#" class="btn btn-icon btn-trigger nk-chat-hide ms-n1">
                                    <em class="icon ni ni-arrow-left"></em>
                                </a>
                            </li>
                            <li class="nk-chat-head-user">
                                <div class="user-card">
                                    <div class="user-avatar" id="chat-user-avatar">
                                        <span class="avatar-text" id="chat-user-initial"></span>
                                    </div>
                                    <div class="user-info">
                                        <div class="lead-text" id="chat-user-name"></div>
                                        <div class="sub-text" id="chat-user-status"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="nk-chat-head-tools">


                            <li class="d-none d-sm-block">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger text-primary"
                                        data-bs-toggle="dropdown">
                                        <em class="icon ni ni-setting-fill"></em>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="deleteConversation()">
                                                    <em class="icon ni ni-cross-c"></em>
                                                    <span>Remove Conversation</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="nk-chat-panel simplebar-scrollable-y" data-simplebar="init">
                        <div class="simplebar-content" id="messages-container">
                            <!-- Messages will be loaded here -->
                        </div>
                    </div>
                    <div class="nk-chat-editor">
                        <div class="nk-chat-editor-upload ms-n1">
                            <a href="#" class="btn btn-sm btn-icon btn-trigger text-primary toggle-opt"
                                data-target="chat-upload">
                                <em class="icon ni ni-plus-circle-fill"></em>
                            </a>
                            <div class="chat-upload-option" data-content="chat-upload">
                                <ul>
                                    <li><a href="#"><em class="icon ni ni-img-fill"></em></a></li>
                                    <li><a href="#"><em class="icon ni ni-camera-fill"></em></a></li>
                                    <li><a href="#"><em class="icon ni ni-mic"></em></a></li>
                                    <li><a href="#"><em class="icon ni ni-grid-sq"></em></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-chat-editor-form">
                            <form id="message-form">
                                <div class="form-control-wrap">
                                    <textarea class="form-control form-control-simple no-resize" rows="1" id="message-input"
                                        placeholder="Type your message..."></textarea>
                                </div>
                            </form>
                        </div>
                        <ul class="nk-chat-editor-tools g-2">
                            <li>
                                <a href="#" class="btn btn-sm btn-icon btn-trigger text-primary">
                                    <em class="icon ni ni-happyf-fill"></em>
                                </a>
                            </li>
                            <li>
                                <button type="submit" form="message-form" class="btn btn-round btn-primary btn-icon">
                                    <em class="icon ni ni-send-alt"></em>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="no-conversation-selected" class="text-center p-5">
                    <h4>Select a conversation to start chatting</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Group Modal -->
    <div class="modal fade" id="create-group-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="create-group-form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Group Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Participants</label>
                            <div class="form-control-wrap">
                                <select class="form-select" name="participants[]" multiple required
                                    data-placeholder="Select participants">
                                    @foreach ($users as $participant)
                                        <option value="{{ $participant->id }}">
                                            {{ $participant->name }} ({{ $participant->role }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Direct Chat Modal -->
    <div class="modal fade" id="direct-chat-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Start New Chat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="direct-chat-form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Select User</label>
                            <div class="form-control-wrap">
                                <select class="form-select" name="user_id" required>
                                    <option value="">Select a user to chat with</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->role }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Start Chat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_content')
    <script>
        let currentConversationId = null;

        function showCreateGroupModal() {
            const modal = new bootstrap.Modal(document.getElementById('create-group-modal'));
            modal.show();
        }

        function hideCreateGroupModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('create-group-modal'));
            modal.hide();
        }

        // Initialize select2 for better user selection
        document.addEventListener('DOMContentLoaded', function() {
            $('select[name="participants[]"]').select2({
                width: '100%',
                placeholder: 'Select participants',
                allowClear: true
            });
        });

        document.getElementById('create-group-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = {
                name: formData.get('name'),
                participants: Array.from(formData.getAll('participants[]')).map(Number)
            };

            fetch('/chat/group', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    hideCreateGroupModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to create group. Please try again.');
                });
        });

        function loadConversation(conversationId) {
            currentConversationId = conversationId;
            const chatArea = document.getElementById('chat-area');
            const noConversation = document.getElementById('no-conversation-selected');

            chatArea.style.display = 'block';
            noConversation.style.display = 'none';

            // Load conversation details
            fetch(`/chat/${conversationId}`)
                .then(response => response.json())
                .then(conversation => {
                    const userName = document.getElementById('chat-user-name');
                    const userInitial = document.getElementById('chat-user-initial');

                    if (conversation.is_group) {
                        userName.textContent = conversation.name || 'Unnamed Group';
                        userInitial.textContent = conversation.name ? conversation.name.charAt(0).toUpperCase() : 'G';
                    } else {
                        const otherParticipant = conversation.participants.find(p => p.user_id !==
                            {{ auth()->id() }});
                        if (otherParticipant) {
                            userName.textContent = otherParticipant.name;
                            userInitial.textContent = otherParticipant.name.charAt(0).toUpperCase();
                        } else {
                            userName.textContent = 'Unknown User';
                            userInitial.textContent = 'U';
                        }
                    }
                });

            // Load messages
            fetch(`/chat/${conversationId}/messages`)
                .then(response => response.json())
                .then(messages => {
                    const container = document.getElementById('messages-container');
                    container.innerHTML = messages.map(message => `
                    <div class="chat ${message.user_id === {{ auth()->id() }} ? 'is-me' : 'is-you'}">
                        <div class="chat-content">
                            <div class="chat-bubbles">
                                <div class="chat-bubble">
                                    <div class="chat-msg">${message.message || ''}</div>
                                    ${message.media ? `
                                                                                                                    <div class="chat-media-preview">
                                                                                                                        <img src="/storage/${message.media.path}" alt="Media">
                                                                                                                    </div>
                                                                                                                ` : ''}
                                    <ul class="chat-msg-more">
                                        <li class="d-none d-sm-block">
                                            <a href="#" class="btn btn-icon btn-sm btn-trigger">
                                                <em class="icon ni ni-reply-fill"></em>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-icon btn-sm btn-trigger dropdown-toggle" data-bs-toggle="dropdown">
                                                    <em class="icon ni ni-more-h"></em>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li class="d-sm-none">
                                                            <a href="#">
                                                                <em class="icon ni ni-reply-fill"></em>
                                                                Reply
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" onclick="editMessage(${message.id})">
                                                                <em class="icon ni ni-pen-alt-fill"></em>
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" onclick="deleteMessage(${message.id})">
                                                                <em class="icon ni ni-trash-fill"></em>
                                                                Remove
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <ul class="chat-meta">
                                <li>${message.user?.name || 'Unknown User'}</li>
                                <li>${message.created_at ? new Date(message.created_at).toLocaleString() : ''}</li>
                            </ul>
                        </div>
                    </div>
                `).join('');
                    container.scrollTop = container.scrollHeight;
                });
        }

        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!currentConversationId) return;

            const message = document.getElementById('message-input').value;
            if (!message.trim()) return;

            fetch(`/chat/${currentConversationId}/message`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('message-input').value = '';
                    loadConversation(currentConversationId);
                });
        });

        function toggleMute(conversationId) {
            fetch(`/chat/${conversationId}/mute`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    window.location.reload();
                });
        }

        function deleteConversation(conversationId) {
            if (confirm('Are you sure you want to delete this conversation?')) {
                fetch(`/chat/${conversationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        window.location.reload();
                    });
            }
        }

        function editMessage(messageId) {
            const newMessage = prompt('Edit your message:');
            if (newMessage) {
                fetch(`/chat/message/${messageId}/edit`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            message: newMessage
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadConversation(currentConversationId);
                    });
            }
        }

        function deleteMessage(messageId) {
            if (confirm('Are you sure you want to delete this message?')) {
                fetch(`/chat/message/${messageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadConversation(currentConversationId);
                    });
            }
        }

        function showDirectChatModal() {
            const modal = new bootstrap.Modal(document.getElementById('direct-chat-modal'));
            modal.show();
        }

        document.getElementById('direct-chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const userId = formData.get('user_id');

            if (!userId) {
                alert('Please select a user to chat with');
                return;
            }

            fetch('/chat/direct', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('direct-chat-modal'));
                        modal.hide();
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to start chat. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to start chat. Please try again.');
                });
        });
    </script>
@endsection
