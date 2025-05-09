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
                                @isset($conversations)
                                    @foreach ($conversations as $conversation)
                                        <li id="active_chat_{{ $conversation->id }}"
                                            class="chat-item {{ $conversation->unread_count > 0 ? 'is-unread' : '' }}">
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
                                            {{-- <div class="chat-actions">
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
                                            </div> --}}
                                        </li>
                                    @endforeach
                                @endisset
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
                                            {{-- @if ($conversation->type === 'group' && $conversation->participants->where('pivot.is_creator', true)->first()->id === auth()->id()) --}}
                                            <li id="edit_list_box" style="display: none">
                                                <a class="dropdown-item" href="#" id="edit_group_id" data-id=""
                                                    onclick="showEditGroupModal()">
                                                    <em class="icon ni ni-edit"></em>
                                                    <span>Edit Group</span>
                                                </a>
                                            </li>
                                            {{-- @endif --}}
                                            @if (auth()->user()->role == 'admin')
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="deleteConversation()">
                                                        <em class="icon ni ni-cross-c"></em>
                                                        <span>Remove Conversation</span>
                                                    </a>
                                                </li>
                                            @endif

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
                                    <li><a href="#" onclick="openChatImageModal(currentConversationId)"><em
                                                class="icon ni ni-img-fill"></em></a></li>
                                    {{-- <li><a href="#"><em class="icon ni ni-camera-fill"></em></a></li>
                                    <li><a href="#"><em class="icon ni ni-mic"></em></a></li>
                                    <li><a href="#"><em class="icon ni ni-grid-sq"></em></a></li> --}}
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
                            {{-- <li>
                                <a href="#" class="btn btn-sm btn-icon btn-trigger text-primary">
                                    <em class="icon ni ni-happyf-fill"></em>
                                </a>
                            </li> --}}
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

    <!-- Edit Group Modal -->
    <div class="modal fade" id="edit-group-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-group-form">
                    <div class="modal-body">
                        <input type="hidden" name="conversation_id" id="edit-group-id">
                        <div class="form-group">
                            <label class="form-label">Group Name</label>
                            <input type="text" class="form-control" name="name" id="edit-group-name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Participants</label>
                            <div class="form-control-wrap">
                                <select class="form-select" name="participants[]" id="edit-group-participants" multiple
                                    required data-placeholder="Select participants">
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
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.chat-image-upload-modal')
@endsection

@section('script_content')
    <script>
        let currentConversationId = null;
        let currentGroupId = null;

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
            const chatItems = document.querySelectorAll('.chat-item');

            chatItems.forEach(item => {
                item.removeAttribute('style');
            });
            const activeChat = document.getElementById('active_chat_' + conversationId);
            activeChat.style.background = "#ebeef2"


            chatArea.style.display = 'block';
            noConversation.style.display = 'none';

            // Load conversation details
            fetch(`/chat/${conversationId}`)
                .then(response => response.json())
                .then(response => {
                    const conversation = response.conversation;
                    let editListBox = document.getElementById('edit_list_box');
                    const userName = document.getElementById('chat-user-name');
                    const userInitial = document.getElementById('chat-user-initial');
                    editListBox.style.display = 'none';

                    if (conversation.type === 'group') {
                        userName.textContent = conversation.name || 'Unnamed Group';
                        userInitial.textContent = conversation.name ? conversation.name.charAt(0).toUpperCase() : 'G';
                        const adminUser = conversation.participants.find(p =>
                            p.user_id === {{ auth()->id() }}
                        );

                        if (adminUser && adminUser.is_creator) {
                            editListBox.style.display = 'block';
                        }
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
            loadMessages(conversationId);

            // Start checking for new messages
            startMessagePolling(conversationId);
        }

        function loadMessages(conversationId) {
            fetch(`/chat/${conversationId}/messages`)
                .then(response => response.json())
                .then(messages => {

                    const chatPanel = document.querySelector('.nk-chat-panel');
                    const container = document.getElementById('messages-container');
                    container.innerHTML = messages.map(message => `
                        <div class="chat ${message.user_id === {{ auth()->id() }} ? 'is-me' : 'is-you'}">
                            <div class="chat-content">
                                <div class="chat-bubbles">
                                    <div class="chat-bubble">
                                        <div>
                                        ${message.message ? `
                                                                                    <div class="chat-msg">
                                                                                        ${message.message}
                                                                                    </div>` : ''}
                                        ${message.media ? `
                                                                                                                                <div class="chat-media-preview">
                                                                                                                                    <img src="${message.media.path}" width="200" alt="Media">
                                                                                                                                </div>
                                                                                                                            ` : ''}
                                                                </div>
                                    </div>
                                </div>
                                <ul class="chat-meta">
                                    <li>${message.user?.name || 'Unknown User'}</li>
                                    <li>${message.created_at ? new Date(message.created_at).toLocaleString() : ''}</li>
                                </ul>
                            </div>
                        </div>
                    `).join('');
                    chatPanel.scrollTop = chatPanel.scrollHeight;
                });
        }

        let messagePollingInterval = null;

        function startMessagePolling(conversationId) {
            // Clear any existing interval
            if (messagePollingInterval) {
                clearInterval(messagePollingInterval);
            }

            // Start new interval
            messagePollingInterval = setInterval(() => {
                if (currentConversationId === conversationId) {
                    checkNewMessages(conversationId);
                }
            }, 3000); // Check every 3 seconds
        }

        function checkNewMessages(conversationId) {
            fetch(`/chat/${conversationId}/check-latest`)
                .then(response => response.json())
                .then(data => {
                    if (data.hasNewMessages) {
                        loadMessages(conversationId);
                    }
                })
                .catch(error => {
                    console.error('Error checking for new messages:', error);
                });
        }

        // function checkNewMessages(conversationId) {
        //     fetch(`/chat/${conversationId}/messages/latest`)
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.hasNewMessages) {
        //                 loadMessages(conversationId);
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Error checking for new messages:', error);
        //         });
        // }

        // Stop polling when leaving the page
        window.addEventListener('beforeunload', function() {
            if (messagePollingInterval) {
                clearInterval(messagePollingInterval);
            }
        });

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

        // function toggleMute(conversationId) {
        //     fetch(`/chat/${conversationId}/mute`, {
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //             }
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             window.location.reload();
        //         });
        // }

        function deleteConversation(conversationId) {
            if (!currentConversationId) return;
            if (confirm('Are you sure you want to delete this conversation?')) {
                fetch(`/chat/${currentConversationId}`, {
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

        // function editMessage(messageId) {
        //     const newMessage = prompt('Edit your message:');
        //     if (newMessage) {
        //         fetch(`/chat/message/${messageId}/edit`, {
        //                 method: 'POST',
        //                 headers: {
        //                     'Content-Type': 'application/json',
        //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //                 },
        //                 body: JSON.stringify({
        //                     message: newMessage
        //                 })
        //             })
        //             .then(response => response.json())
        //             .then(data => {
        //                 loadConversation(currentConversationId);
        //             });
        //     }
        // }

        // function deleteMessage(messageId) {
        //     if (confirm('Are you sure you want to delete this message?')) {
        //         fetch(`/chat/message/${messageId}`, {
        //                 method: 'DELETE',
        //                 headers: {
        //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //                 }
        //             })
        //             .then(response => response.json())
        //             .then(data => {
        //                 loadConversation(currentConversationId);
        //             });
        //     }
        // }

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

        function showEditGroupModal() {
            let conversationId = currentConversationId;
            // Load conversation details
            fetch(`/chat/${conversationId}`)
                .then(response => response.json())
                .then(response => {
                    const conversation = response.conversation;

                    console.log(conversation);

                    // Set form values
                    document.getElementById('edit-group-id').value = conversation.id;
                    document.getElementById('edit-group-name').value = conversation.name;

                    // First destroy any existing Select2 instance
                    if ($('#edit-group-participants').hasClass('select2-hidden-accessible')) {
                        $('#edit-group-participants').select2('destroy');
                    }

                    // Initialize select2 for participants
                    $('#edit-group-participants').select2({
                        width: '100%',
                        placeholder: 'Select participants',
                        allowClear: true
                    });

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('edit-group-modal'));
                    modal.show();

                    // Set selected participants
                    const participantIds = conversation.participants.map(p => p.user_id);
                    console.log(participantIds);
                    // $('#edit-group-participants').val(participantIds).trigger('change');
                    setTimeout(() => {
                        $('#edit-group-participants').val(participantIds).trigger('change');
                    }, 100);


                });
        }

        document.getElementById('edit-group-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const conversationId = document.getElementById('edit-group-id').value;
            const formData = new FormData(this);
            const data = {
                name: formData.get('name'),
                participants: Array.from(formData.getAll('participants[]')).map(Number)
            };

            fetch(`/chat/group/${conversationId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('edit-group-modal'));
                        modal.hide();
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to update group. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update group. Please try again.');
                });
        });

        // chat image upload
        document.addEventListener('DOMContentLoaded', function() {
            const modal = $('#chatImageUploadModal');
            const form = $('#chatImageUploadForm');
            const uploadBtn = $('#uploadChatImageBtn');
            const progressBar = $('.progress');
            const progressBarInner = $('.progress-bar');
            const conversationIdInput = $('#conversation_id');

            // Function to open modal with conversation ID
            window.openChatImageModal = function(conversationId) {
                conversationIdInput.val(conversationId);
                modal.modal('show');
            };

            uploadBtn.on('click', function() {
                const formData = new FormData(form[0]);

                $.ajax({
                    url: '{{ route('chat.upload.image') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const percent = Math.round((e.loaded / e.total) * 100);
                                progressBar.show();
                                progressBarInner.css('width', percent + '%');
                            }
                        });
                        return xhr;
                    },
                    success: function(response) {
                        if (response.success) {
                            modal.modal('hide');
                            // Trigger the image upload callback if it exists
                            if (typeof window.onChatImageUploaded === 'function') {
                                window.onChatImageUploaded(response);
                            }
                        } else {
                            alert('Upload failed: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Upload failed. Please try again.');
                    },
                    complete: function() {
                        progressBar.hide();
                        progressBarInner.css('width', '0%');
                    }
                });
            });
        });
    </script>
@endsection
