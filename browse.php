<h2>Browse Shared Messages</h2>
<div class="search-bar">
    <input type="text" id="search-input" placeholder="Search by recipient name...">
    <button id="search-button">Search</button>
</div>
<div id="messages-container">
    <?php if (count($notes) > 0): ?>
        <?php foreach ($notes as $note): ?>
            <?php $formattedDate = formatDate($note['created_at']); ?>
            <div class="message" 
                 data-recipient="<?= htmlspecialchars(strtolower($note['to_person'])) ?>"
                 data-note="<?= htmlspecialchars($note['note']) ?>"
                 data-music-link="<?= htmlspecialchars($note['music_link']) ?>"
                 data-timestamp="<?= htmlspecialchars($formattedDate['sent_on']) ?>">
                <p><strong>To:</strong> <?= htmlspecialchars($note['to_person']) ?></p>
                <p class="note-text"><?= nl2br(htmlspecialchars($note['note'])) ?></p>
                <?php if ($note['is_spotify_link']): ?>
                    <?= $note['spotify_embed_code'] ?>
                <?php elseif ($note['music_link']): ?>
                    <p><a href="<?= htmlspecialchars($note['music_link']) ?>" target="_blank">🎵 Listen to the song</a></p>
                <?php endif; ?>
                <p class="timestamp"><strong>Sent on:</strong> <?= htmlspecialchars($formattedDate['sent_on']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-messages">No messages to display yet. Share your thoughts!</p>
    <?php endif; ?>
</div>

<!-- Popup for full message -->
<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
    <p><strong>To:</strong> <span id="popup-recipient"></span></p>
    <div class="quoted-message note-text" id="popup-note"></div>
    <p id="popup-music-link"></p>
    <p class="timestamp"><strong>Sent on:</strong> <span id="popup-timestamp"></span></p>
    <button class="close-btn" id="close-btn">Close</button>
</div>