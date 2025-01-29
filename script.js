document.addEventListener('DOMContentLoaded', function() {
    const messageContainers = document.querySelectorAll('.message');
    const popup = document.getElementById('popup');
    const overlay = document.getElementById('overlay');
    const popupRecipient = document.getElementById('popup-recipient');
    const popupNote = document.getElementById('popup-note');
    const popupMusicLink = document.getElementById('popup-music-link');
    const popupTimestamp = document.getElementById('popup-timestamp');
    const closeBtn = document.getElementById('close-btn');
    const searchButton = document.getElementById('search-button');

    messageContainers.forEach(container => {
        container.addEventListener('click', () => {
            const recipient = container.getAttribute('data-recipient');
            const note = container.getAttribute('data-note');
            const musicLink = container.getAttribute('data-music-link');
            const timestamp = container.getAttribute('data-timestamp');

            popupRecipient.textContent = recipient;
            popupNote.innerHTML = note.replace(/\n/g, '<br>');
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

    closeBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', closePopup);

    function closePopup() {
        popup.style.display = 'none';
        overlay.style.display = 'none';
    }

    if (searchButton) {
        searchButton.addEventListener('click', filterMessages);
    }

    function filterMessages() {
        const searchQuery = document.getElementById('search-input').value.trim().toLowerCase();
        const messages = document.querySelectorAll('.message');

        messages.forEach(message => {
            const recipient = message.getAttribute('data-recipient');
            if (recipient.includes(searchQuery)) {
                message.style.display = 'block';
            } else {
                message.style.display = 'none';
            }
        });
    }
});