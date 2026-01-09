// Listen for MessageSent event and update UI
import Echo from 'laravel-echo';

// Get current user ID (assume available globally, e.g. window.userId)
const userId = window.userId;

if (userId && window.Echo) {
    window.Echo.private(`user.${userId}`)
        .listen('MessageSent', (data) => {
            // You can customize this to update your chat/message UI
            // Example: append message to chat box
            const chatBox = document.getElementById('chatBox');
            if (chatBox) {
                const msgDiv = document.createElement('div');
                msgDiv.className = 'chat-message';
                msgDiv.innerHTML = `<strong>${data.sender.pet_name}:</strong> ${data.message} <span class='chat-time'>${data.created_at}</span>`;
                chatBox.appendChild(msgDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            }
            // Optionally, play sound or show notification
        });
}
