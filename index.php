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
    <style>
        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1f4037, #99f2c8);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
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
            padding: 0 20px; /* Add spacing to the left and right */
        }

        /* Logo Styling */
        .logo h1 {
            font-size: 2.5rem;
            margin: 0;
            padding: 0 20px; /* Add spacing to the left and right of "Drafted" */
        }

        /* Navigation Bar Styling */
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

        /* Container Styling */
        .container {
            width: 90%;
            max-width: 700px; /* Increased max-width for 2-per-row layout */
            background: rgba(0, 0, 0, 0.6);
            margin: 20px auto;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        /* Search Bar Styling */
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: center; /* Center the search bar */
            gap: 10px;
        }

        #search-input {
            width: 50%; /* Adjust the width as needed */
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

        /* Messages Container Styling */
        #messages-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 messages per row */
            gap: 20px; /* Spacing between messages */
        }

        /* Message Cards Styling */
        .message {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            text-align: left;
        }

        .message strong {
            color: #ff7e5f;
        }

        .message a {
            color: #feb47b;
        }

        .message a:hover {
            text-decoration: underline;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 500px; /* Limit form width for better readability */
            margin: 0 auto; /* Center the form */
        }

        form input, form textarea, form button {
            width: 100%; /* Full width for form elements */
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
            resize: vertical; /* Allow vertical resizing of textarea */
        }

        form button {
            background: #ff7e5f;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            width: auto; /* Let the button size adjust to its content */
            padding: 10px 20px; /* Adjust padding for better appearance */
        }

        form button:hover {
            background: #feb47b;
        }

        /* Footer */
        footer {
            margin-top: auto;
            padding: 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <h1>Drafted</h1>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="?page=browse">Browse</a>
                <a href="?page=submit">Submit</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <!-- PHP Content -->
        <?php
        $page = $_GET['page'] ?? 'home';

        if ($page === 'browse') {
            echo '<h2>Browse Shared Messages</h2>';
            // Search Bar
            echo '<div class="search-bar">';
            echo '<input type="text" id="search-input" placeholder="Search by recipient name...">';
            echo '<button id="search-button">Search</button>';
            echo '</div>';
            // Messages Container
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
            echo '<p>A safe space to express your thoughts anonymously. Share rants, unspoken words, or heartfelt messages. Browse shared messages or submit your own today!</p>';
        }
        ?>
    </div>

    <footer>
        <p>© 2025 Drafted. All Rights Reserved.</p>
    </footer>

    <script>
        // JavaScript for Search Functionality
        document.getElementById('search-button').addEventListener('click', function() {
            filterMessages();
        });

        document.getElementById('search-input').addEventListener('input', function() {
            filterMessages();
        });

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
    </script>
</body>
</html>
