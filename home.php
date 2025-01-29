<h2>Welcome to Drafted</h2>
<p>A safe space to express your thoughts anonymously. Share rants, unspoken words, or heartfelt messages to someone. Browse shared messages or submit your own today!</p>

<div class="button-container">
    <a href="?page=submit" style="text-decoration: none;">
        <button>Submit a Message</button>
    </a>
    <a href="?page=browse" style="text-decoration: none;">
        <button>Browse Messages</button>
    </a>
</div>

<div id="messages-container">
    <?php
    $customMessages = [
        [
            'to_person' => 'Everyone',
            'note' => 'Welcome to Drafted! Share your thoughts, rants, or heartfelt messages anonymously to someone.',
            'music_link' => '',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'to_person' => 'Stranger',
            'note' => 'Life is short. Say what you need to say before it\'s too late.',
            'music_link' => '',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'to_person' => 'Future Me',
            'note' => 'I hope you\'re proud of who you\'ve become. Keep going!',
            'music_link' => '',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'to_person' => 'You',
            'note' => 'You are stronger than you think. Don\'t give up!',
            'music_link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];

    foreach ($customMessages as $message):
        $formattedDate = formatDate($message['created_at']);
    ?>
        <div class="message" 
             data-recipient="<?= htmlspecialchars(strtolower($message['to_person'])) ?>"
             data-note="<?= htmlspecialchars($message['note']) ?>"
             data-music-link="<?= htmlspecialchars($message['music_link']) ?>"
             data-timestamp="<?= htmlspecialchars($formattedDate['sent_on']) ?>">
            <p><strong>To:</strong> <?= htmlspecialchars($message['to_person']) ?></p>
            <p class="note-text"><?= nl2br(htmlspecialchars($message['note'])) ?></p>
            <?php if ($message['music_link']): ?>
                <p><a href="<?= htmlspecialchars($message['music_link']) ?>" target="_blank">🎵 Listen to the song</a></p>
            <?php endif; ?>
            <p class="timestamp"><strong>Sent on:</strong> <?= htmlspecialchars($formattedDate['sent_on']) ?></p>
        </div>
    <?php endforeach; ?>
</div>