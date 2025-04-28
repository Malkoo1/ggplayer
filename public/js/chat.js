let lastMessageId = null;
let checkMessagesInterval = null;
let checkAllConversationsInterval = null;

// function startCheckingMessages() {
//     if (checkMessagesInterval) {
//         clearInterval(checkMessagesInterval);
//     }

//     checkMessagesInterval = setInterval(() => {
//         const conversationId = window.location.pathname.split('/').pop();
//         if (conversationId) {
//             checkLatestMessages(conversationId);
//         }
//     }, 5000); // Check every 5 seconds
// }

// function checkLatestMessages(conversationId) {
//     fetch(`/chat/${conversationId}/check-latest`)
//         .then(response => response.json())
//         .then(data => {
//             if (data.hasNewMessages) {
//                 // If there are new messages, reload the messages
//                 loadMessages(conversationId);
//             }
//         })
//         .catch(error => console.error('Error checking latest messages:', error));
// }

// function loadMessages(conversationId) {
//     fetch(`/chat/${conversationId}/messages`)
//         .then(response => response.json())
//         .then(data => {
//             const messagesContainer = document.querySelector('.chat-messages');
//             messagesContainer.innerHTML = '';

//             data.messages.forEach(message => {
//                 const messageElement = createMessageElement(message);
//                 messagesContainer.appendChild(messageElement);
//             });

//             // Scroll to bottom
//             messagesContainer.scrollTop = messagesContainer.scrollHeight;

//             // Update last message ID
//             if (data.messages.length > 0) {
//                 lastMessageId = data.messages[data.messages.length - 1].id;
//             }
//         })
//         .catch(error => console.error('Error loading messages:', error));
// }

function showNotification(conversation) {
    // Check if browser supports notifications
    if (!("Notification" in window)) {
        console.log("This browser does not support desktop notification");
        return;
    }

    // Check if permission is already granted
    if (Notification.permission === "granted") {
        createNotification(conversation);
    }
    // Otherwise, ask for permission
    else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(function (permission) {
            if (permission === "granted") {
                createNotification(conversation);
            }
        });
    }
}

function createNotification(conversation) {
    const notification = new Notification(conversation.name, {
        body: conversation.lastMessage,
        icon: '/images/notification-icon.png' // Add your notification icon
    });

    notification.onclick = function () {
        window.location.href = `/chat/${conversation.id}`;
    };
}

function checkAllConversations() {
    fetch('/chat/check-all')
        .then(response => response.json())
        .then(data => {
            const notificationContainer = document.querySelector('.nk-notification');
            if (!notificationContainer) return;

            // Clear existing notifications
            notificationContainer.innerHTML = '';

            // Add new notifications
            data.unreadConversations.forEach(conversation => {
                const timeAgo = new Date(conversation.timestamp).toLocaleString();
                const notificationItem = document.createElement('div');
                notificationItem.className = 'nk-notification-item dropdown-inner';
                notificationItem.innerHTML = `
                    <div class="nk-notification-icon">
                        <em class="icon icon-circle ${conversation.iconBg} ${conversation.icon}"></em>
                    </div>
                    <div class="nk-notification-content">
                        <div class="nk-notification-text">
                            <a href="/chat" class="text-dark">
                                ${conversation.name}: ${conversation.lastMessage}
                            </a>
                        </div>
                        <div class="nk-notification-time">${timeAgo}</div>
                    </div>
                `;
                notificationContainer.appendChild(notificationItem);
            });

            // Update notification count
            const notificationCount = document.querySelector('.icon-statusss');
            if (notificationCount) {
                const count = data.unreadConversations.length;
                if (count > 0) {
                    notificationCount.classList.add("icon-status", "icon-status-info");

                }
                // notificationCount.style.display = count > 0 ? 'block' : 'none';
                // notificationCount.classList.add = "icon-status icon-status-info";
                // notificationCount.textContent = count > 99 ? '99+' : count;
                // notificationCount.innerHTML = `<em class="icon ni ni-bell"></em>`;
            }
        })
        .catch(error => console.error('Error checking all conversations:', error));
}

function startCheckingAllConversations() {
    if (checkAllConversationsInterval) {
        clearInterval(checkAllConversationsInterval);
    }

    checkAllConversationsInterval = setInterval(() => {
        checkAllConversations();
    }, 10000); // Check every 10 seconds
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

// Start checking for new messages when the page loads
document.addEventListener('DOMContentLoaded', () => {
    // const conversationId = window.location.pathname.split('/').pop();
    // if (conversationId) {
    //     startCheckingMessages();
    // }
    startCheckingAllConversations();
});

// Stop checking when leaving the page
window.addEventListener('beforeunload', () => {
    if (checkMessagesInterval) {
        clearInterval(checkMessagesInterval);
    }
    if (checkAllConversationsInterval) {
        clearInterval(checkAllConversationsInterval);
    }
}); 