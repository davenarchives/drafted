<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include 'db.php';

// Handle form submission for new notes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $to_person = $_POST['to_person'] ?? '';
    $note = $_POST['note'] ?? '';
    $music_link = $_POST['music_link'] ?? '';

    if (!empty($note)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes (to_person, note, music_link) VALUES (:to_person, :note, :music_link)");
            $stmt->execute([
                ':to_person' => $to_person,
                ':note' => $note,
                ':music_link' => $music_link
            ]);
        } catch (PDOException $e) {
            die("Failed to submit message: " . $e->getMessage());
        }
    }
}

// Handle deletion of a note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;

    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM notes WHERE id = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            die("Failed to delete message: " . $e->getMessage());
        }
    }
}

// Fetch all notes
try {
    $stmt = $pdo->query("SELECT * FROM notes ORDER BY created_at DESC");
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch messages: " . $e->getMessage());
}

// Function to format the date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return [
        'posted_on' => $date->format('Y-m-d'), // Format for "Posted on"
        'sent_on' => $date->format('F j, Y')   // Format for "Sent on"
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drafted</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Reenie+Beanie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="index.php" style="text-decoration: none; color: inherit;">
                    <h1>Drafted</h1>
                </a>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="?page=browse">Browse</a>
                <a href="?page=submit">Submit</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php
        $page = $_GET['page'] ?? 'home';

        if ($page === 'browse') {
            echo '<h2>Browse Shared Messages</h2>';
            echo '<div class="search-bar">';
            echo '<input type="text" id="search-input" placeholder="Search by recipient name...">';
            echo '<button id="search-button">Search</button>';
            echo '</div>';
            echo '<div id="messages-container">';
            if (count($notes) > 0) {
                foreach ($notes as $note) {
                    $formattedDate = formatDate($note['created_at']);
                    echo '<div class="message" data-recipient="' . htmlspecialchars(strtolower($note['to_person'])) . '" data-note="' . htmlspecialchars($note['note']) . '" data-music-link="' . htmlspecialchars($note['music_link']) . '" data-timestamp="' . htmlspecialchars($formattedDate['sent_on']) . '">';
                    echo '<p><strong>To:</strong> ' . htmlspecialchars($note['to_person']) . '</p>';
                    echo '<p class="note-text">' . nl2br(htmlspecialchars($note['note'])) . '</p>'; // Italicized note text
                    if ($note['music_link']) {
                        echo '<p><a href="' . htmlspecialchars($note['music_link']) . '" target="_blank">🎵 Listen to the song</a></p>';
                    }
                    echo '<p class="timestamp"><strong>Sent on:</strong> ' . htmlspecialchars($formattedDate['sent_on']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-messages">No messages to display yet. Share your thoughts!</p>';
            }
            echo '</div>';
        } elseif ($page === 'submit') {
            echo '<h2>Submit a Message</h2>';
            echo '<form action="index.php" method="POST">';
            echo '<input type="text" name="to_person" placeholder="To (e.g., Recipient Name)" required>';
            echo '<textarea name="note" rows="4" placeholder="Write your note here..." required></textarea>';
            echo '<input type="text" name="music_link" placeholder="Optional: Add a music link">';
            echo '<input type="hidden" name="action" value="add">';
            echo '<button type="submit">Submit Note</button>';
            echo '</form>';
        } else {
            echo '<h2>Welcome to Drafted</h2>';
            echo '<p>A safe space to express your thoughts anonymously. Share rants, unspoken words, or heartfelt messages to someone. Browse shared messages or submit your own today!</p>';

            // Add buttons for Submit and Browse
            echo '<div class="button-container">';
            echo '<a href="?page=submit" style="text-decoration: none;">';
            echo '<button>Submit a Message</button>';
            echo '</a>';
            echo '<a href="?page=browse" style="text-decoration: none;">';
            echo '<button>Browse Messages</button>';
            echo '</a>';
            echo '</div>';

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

            echo '<div id="messages-container">';
            foreach ($customMessages as $message) {
                $formattedDate = formatDate($message['created_at']);
                echo '<div class="message" data-recipient="' . htmlspecialchars(strtolower($message['to_person'])) . '" data-note="' . htmlspecialchars($message['note']) . '" data-music-link="' . htmlspecialchars($message['music_link']) . '" data-timestamp="' . htmlspecialchars($formattedDate['sent_on']) . '">';
                echo '<p><strong>To:</strong> ' . htmlspecialchars($message['to_person']) . '</p>';
                echo '<p class="note-text">' . nl2br(htmlspecialchars($message['note'])) . '</p>'; // Italicized note text
                if ($message['music_link']) {
                    echo '<p><a href="' . htmlspecialchars($message['music_link']) . '" target="_blank">🎵 Listen to the song</a></p>';
                }
                echo '<p class="timestamp"><strong>Sent on:</strong> ' . htmlspecialchars($formattedDate['sent_on']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
    </div>

    <!-- Popup for full message -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <p><strong>To:</strong> <span id="popup-recipient"></span></p>
        <div class="quoted-message note-text" id="popup-note"></div> <!-- Italicized note text -->
        <p id="popup-music-link"></p>
        <p class="timestamp"><strong>Sent on:</strong> <span id="popup-timestamp"></span></p>
        <button class="close-btn" id="close-btn">Close</button>
    </div>

    <footer>
        <p>© 2025 Drafted. All Rights Reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>