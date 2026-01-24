/**
 * UgaEats AI Chatbot
 * Powered by Google Gemini
 */

(function() {
    'use strict';

    // Create chatbot HTML
    const chatbotHTML = `
        <div id="ugaeats-chatbot" class="chatbot-container">
            <!-- Chat Toggle Button -->
            <button id="chatbot-toggle" class="chatbot-toggle" aria-label="Open chat">
                <svg class="chat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <svg class="close-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span class="notification-badge" style="display: none;">1</span>
            </button>

            <!-- Chat Window -->
            <div id="chatbot-window" class="chatbot-window" style="display: none;">
                <div class="chatbot-header">
                    <div class="chatbot-header-info">
                        <div class="chatbot-avatar">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h4>UgaEats Assistant</h4>
                            <span class="status-indicator">🟢 Online</span>
                        </div>
                    </div>
                    <button id="chatbot-close" class="chatbot-close" aria-label="Close chat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                
                <div id="chatbot-messages" class="chatbot-messages">
                    <div class="message bot-message">
                        <div class="message-content">
                            <p>Hello! 👋 Welcome to UgaEats! I'm your AI assistant. How can I help you today?</p>
                        </div>
                        <span class="message-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    </div>
                </div>

                <div class="chatbot-suggestions">
                    <button class="suggestion-btn" data-message="How do I place an order?">How to order?</button>
                    <button class="suggestion-btn" data-message="Track my order">Track order</button>
                    <button class="suggestion-btn" data-message="What payment methods do you accept?">Payments</button>
                    <button class="suggestion-btn" data-message="I need help">Help</button>
                </div>

                <form id="chatbot-form" class="chatbot-input-form">
                    <input 
                        type="text" 
                        id="chatbot-input" 
                        placeholder="Type your message..." 
                        autocomplete="off"
                        maxlength="500"
                    />
                    <button type="submit" id="chatbot-send" aria-label="Send message" title="Send">
                        ➤
                    </button>
                </form>
                
                <div class="chatbot-footer">
                    <span>Powered by AI ✨</span>
                </div>
            </div>
        </div>
    `;

    // Inject chatbot into page
    document.body.insertAdjacentHTML('beforeend', chatbotHTML);

    // Elements
    const chatbot = document.getElementById('ugaeats-chatbot');
    const toggleBtn = document.getElementById('chatbot-toggle');
    const closeBtn = document.getElementById('chatbot-close');
    const chatWindow = document.getElementById('chatbot-window');
    const messagesContainer = document.getElementById('chatbot-messages');
    const chatForm = document.getElementById('chatbot-form');
    const chatInput = document.getElementById('chatbot-input');
    const suggestionBtns = document.querySelectorAll('.suggestion-btn');
    const chatIcon = toggleBtn.querySelector('.chat-icon');
    const closeIcon = toggleBtn.querySelector('.close-icon');

    let isOpen = false;
    let isTyping = false;

    // Toggle chat window
    function toggleChat() {
        isOpen = !isOpen;
        chatWindow.style.display = isOpen ? 'flex' : 'none';
        chatIcon.style.display = isOpen ? 'none' : 'block';
        closeIcon.style.display = isOpen ? 'block' : 'none';
        
        if (isOpen) {
            chatInput.focus();
            scrollToBottom();
        }
    }

    // Close chat
    function closeChat() {
        isOpen = false;
        chatWindow.style.display = 'none';
        chatIcon.style.display = 'block';
        closeIcon.style.display = 'none';
    }

    // Add message to chat
    function addMessage(text, isUser = false) {
        const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const messageClass = isUser ? 'user-message' : 'bot-message';
        
        const messageHTML = `
            <div class="message ${messageClass}">
                <div class="message-content">
                    <p>${escapeHtml(text)}</p>
                </div>
                <span class="message-time">${time}</span>
            </div>
        `;
        
        messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
        scrollToBottom();
    }

    // Show typing indicator
    function showTyping() {
        if (isTyping) return;
        isTyping = true;
        
        const typingHTML = `
            <div class="message bot-message typing-indicator" id="typing-indicator">
                <div class="message-content">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        `;
        
        messagesContainer.insertAdjacentHTML('beforeend', typingHTML);
        scrollToBottom();
    }

    // Hide typing indicator
    function hideTyping() {
        isTyping = false;
        const typingEl = document.getElementById('typing-indicator');
        if (typingEl) typingEl.remove();
    }

    // Send message to API
    async function sendMessage(message) {
        if (!message.trim()) return;
        
        // Add user message
        addMessage(message, true);
        chatInput.value = '';
        
        // Show typing indicator
        showTyping();
        
        try {
            const response = await fetch('/api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: message }),
            });

            hideTyping();

            const data = await response.json();
            
            if (data.success && data.response) {
                addBotMessage(data.response);
            } else {
                addBotMessage(data.response || "Sorry, I couldn't process that. Please try again.");
            }
        } catch (error) {
            hideTyping();
            console.error('Chatbot error:', error);
            addBotMessage("I'm having trouble connecting. Please check your internet and try again.");
        }
    }

    // Add bot message with formatting
    function addBotMessage(text) {
        const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        // Convert line breaks and format
        const formattedText = text
            .replace(/\n/g, '<br>')
            .replace(/•/g, '&bull;');
        
        const messageHTML = `
            <div class="message bot-message">
                <div class="message-content">
                    <p>${formattedText}</p>
                </div>
                <span class="message-time">${time}</span>
            </div>
        `;
        
        messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
        scrollToBottom();
    }

    // Scroll to bottom of messages
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Event listeners
    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', closeChat);
    
    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        sendMessage(chatInput.value);
    });

    // Suggestion buttons
    suggestionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const message = btn.getAttribute('data-message');
            sendMessage(message);
        });
    });

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen) {
            closeChat();
        }
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (isOpen && !chatbot.contains(e.target)) {
            closeChat();
        }
    });

    console.log('🤖 UgaEats AI Chatbot loaded');
})();
