<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drafted</title>
    <link href="https://fonts.googleapis.com/css2?family=Beanie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* General Body Styling */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1f4037, #99f2c8);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            user-select: none;
        }

        /* Header Styling */
        header {
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Updated h1 Logo Styling */
        .logo h1 {
            font-family: 'Beanie', cursive; /* Use Beanie font */
            font-size: 1.75rem; /* Adjust size as needed */
            font-weight: normal; /* Beanie is a handwritten font, so no bold */
            margin: 0;
            padding: 0 20px;
            color: #ff7e5f; /* Match your theme */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Optional: Add a subtle shadow */
        }

        nav {
            display: flex;
            gap: 20px;
        }

        nav a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            width: 90%;
            max-width: 700px;
            background: rgba(0, 0, 0, 0.6);
            margin: 30px auto;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        #search-input {
            width: 50%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
        }

        #search-button {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            background: #ff7e5f;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        #search-button:hover {
            background: #feb47b;
        }

        #messages-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        /* Updated Message Cards Styling */
        .message {
            font-family: 'Beanie', cursive; /* Use Beanie font for notes */
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            text-align: left;
            font-weight: normal; /* Beanie is a handwritten font, so no bold */
            border-left: 5px solid #ff7e5f;
            margin: 10px 0;
            position: relative;
        }

        .message::before {
            content: '“';
            font-size: 3rem;
            color: #ff7e5f;
            position: absolute;
            top: -10px;
            left: 10px;
            opacity: 0.5;
        }

        .message::after {
            content: '”';
            font-size: 3rem;
            color: #ff7e5f;
            position: absolute;
            bottom: -30px;
            right: 10px;
            opacity: 0.5;
        }

        .message strong {
            color: #ff7e5f;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }

        .message a {
            color: #feb47b;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            text-decoration: none;
        }

        .message a:hover {
            text-decoration: underline;
        }

        .timestamp {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        form input, form textarea, form button {
            width: 100%;
            margin: 10px 0;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
        }

        form input, form textarea {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        form textarea {
            resize: vertical;
        }

        form button {
            background: #ff7e5f;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            width: auto;
            padding: 10px 20px;
        }

        form button:hover {
            background: #feb47b;
        }

        footer {
            margin-top: auto;
            padding: 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Button Container Styling */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px; /* Add spacing between buttons */
            margin-top: 30px; /* Add margin above the buttons */
            margin-bottom: 30px; /* Add margin below the buttons */
        }

        .button-container button {
            background: #ff7e5f;
            color: #fff;
            border: none;
            padding: 10px 20px; /* Smaller padding for smaller buttons */
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .button-container button:hover {
            background: #feb47b;
        }
    </style>
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
                    echo '<div class="message" data-recipient="' . htmlspecialchars(strtolower($note['to_person'])) . '">';
                    echo '<p><strong>To:</strong> ' . htmlspecialchars($note['to_person']) . '</p>';
                    echo '<p>' . htmlspecialchars($note['note']) . '</p>';
                    if ($note['music_link']) {
                        echo '<p><a href="' . htmlspecialchars($note['music_link']) . '" target="_blank">🎵 Listen to the song</a></p>';
                    }
                    echo '<p class="timestamp">Posted on: ' . htmlspecialchars($note['created_at']) . '</p>';
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
                echo '<div class="message" data-recipient="' . htmlspecialchars(strtolower($message['to_person'])) . '">';
                echo '<p><strong>To:</strong> ' . htmlspecialchars($message['to_person']) . '</p>';
                echo '<p>' . htmlspecialchars($message['note']) . '</p>';
                if ($message['music_link']) {
                    echo '<p><a href="' . htmlspecialchars($message['music_link']) . '" target="_blank">🎵 Listen to the song</a></p>';
                }
                echo '<p class="timestamp">Posted on: ' . htmlspecialchars($message['created_at']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
    </div>

    <footer>
        <p>© 2025 Drafted. All Rights Reserved.</p>
    </footer>

    <script>
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
    </script>
</body>
</html>