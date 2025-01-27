// Truncate long messages
const messages = document.querySelectorAll('.message p');
messages.forEach(message => {
    if (message.textContent.length > 100) {
        message.textContent = message.textContent.substring(0, 100) + '...';
    }
});

// Popup functionality
const messageContainers = document.querySelectorAll('.message');
const popup = document.getElementById('popup');
const overlay = document.getElementById('overlay');
const popupRecipient = document.getElementById('popup-recipient');
const popupNote = document.getElementById('popup-note');
const popupMusicLink = document.getElementById('popup-music-link');
const popupTimestamp = document.getElementById('popup-timestamp');
const closeBtn = document.getElementById('close-btn');

messageContainers.forEach(container => {
    container.addEventListener('click', () => {
        const recipient = container.getAttribute('data-recipient');
        const note = container.getAttribute('data-note');
        const musicLink = container.getAttribute('data-music-link');
        const timestamp = container.getAttribute('data-timestamp');

        popupRecipient.textContent = recipient;
        popupNote.innerHTML = note.replace(/\n/g, '<br>'); // Preserve line breaks
        popupTimestamp.textContent = timestamp;

        if (musicLink) {
            popupMusicLink.innerHTML = '<a href="' + musicLink + '" target="_blank">🎵 Listen to the song</a>';
        } else {
            popupMusicLink.innerHTML = '';
        }

        popup.style.display = 'block';
        overlay.style.display = 'block';
    });
});

closeBtn.addEventListener('click', () => {
    popup.style.display = 'none';
    overlay.style.display = 'none';
});

overlay.addEventListener('click', () => {
    popup.style.display = 'none';
    overlay.style.display = 'none';
});

// Search functionality
document.getElementById('search-button').addEventListener('click', function() {
    filterMessages();
});

function filterMessages() {
    const searchQuery = document.getElementById('search-input').value.trim().toLowerCase();
    const messages = document.querySelectorAll('.message');

    messages.forEach(message => {
        const recipient = message.getAttribute('data-recipient');
        if (recipient === searchQuery) {
            message.style.display = 'block';
        } else {
            message.style.display = 'none';
        }
    });
}