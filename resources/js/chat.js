document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const consultationId = document.getElementById('consultationId').value;
    const currentUserId = parseInt(document.getElementById('currentUserId').value);

    // Scroll to bottom on load
    scrollToBottom();

    // Listen for events
    if (typeof Echo !== 'undefined') {
        Echo.private(`consultation.${consultationId}`)
            .listen('.message.sent', (e) => {
                console.log('Message received:', e);
                appendMessage(e.message, e.user);
            });
    } else {
        console.error('Laravel Echo is not defined. Make sure Reverb is configured.');
    }

    // Handle form submission
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            // Optimistic UI update (optional, but good for UX)
            // appendMessage({ message: message, created_at: new Date().toISOString(), user_id: currentUserId }, { id: currentUserId });
            
            // Send to server
            sendMessage(message);
        });
        
        // Handle Enter key to send (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    }

    function sendMessage(message) {
        const url = chatForm.getAttribute('action');
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                // If not using optimistic UI, append here or rely on Echo
                // For now, let's rely on Echo if it's fast enough, or append manually to be safe
                // Note: If we append here AND listen to Echo, we might get duplicates if we don't handle IDs.
                // Best practice: Append immediately with a "pending" state, then update/deduplicate on Echo event.
                // For simplicity in this task, let's just clear input and wait for Echo (or reload if Echo fails).
            } else {
                alert('Failed to send message: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending message');
        });
    }

    function appendMessage(message, user) {
        const isSelf = user.id === currentUserId;
        const alignment = isSelf ? 'sent' : 'received';
        const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        const messageHtml = `
            <div class="message-wrapper ${alignment}">
                ${!isSelf ? `<img src="${user.image_url || '/assets/images/default-avatar.png'}" class="avatar" alt="${user.name}">` : ''}
                <div class="message-bubble">
                    ${message.message_type === 'file' ? 
                        `<a href="${message.attachment_url}" target="_blank" class="${isSelf ? 'text-white' : 'text-primary'}">
                            <i class="fas fa-paperclip me-1"></i>Lampiran
                        </a><br>` : ''}
                    ${message.message}
                    <div class="message-time ${isSelf ? 'text-white-50' : 'text-muted'}">${time}</div>
                </div>
            </div>
        `;

        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
        scrollToBottom();
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});
