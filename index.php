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
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f9;
        color: #333;
    }
    header {
        background: #333;
        color: #fff;
        padding: 10px 20px;
        text-align: center;
    }
    nav {
        background: #444;
        padding: 10px 20px;
        display: flex;
        justify-content: center;
    }
    nav a {
        color: #fff;
        margin: 0 10px;
        text-decoration: none;
        font-size: 1.1em;
    }
    nav a:hover {
        text-decoration: underline;
    }
    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    form {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: center; /* Center align all form elements */
    }
    form input, form textarea, form button {
        width: 80%; /* Adjust width to fit nicely */
        max-width: 500px; /* Optional: Set a maximum width for larger screens */
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box; /* Ensure padding doesn't affect width */
    }
    form button {
        background: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        width: auto; /* Allow button to resize based on text content */
        padding: 10px 20px;
    }
    form button:hover {
        background: #0056b3;
    }
    .message {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        background-color: #f9f9f9;
        position: relative;
    }
    .message a {
        color: #007bff;
        text-decoration: none;
    }
    .message a:hover {
        text-decoration: underline;
    }
    .timestamp {
        font-size: 0.85em;
        color: #666;
        margin-top: 10px;
    }
    .no-messages {
        text-align: center;
        font-size: 1.2em;
        color: #999;
    }
</style>

</head>
<body>
    <header>
        <h1>Drafted</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="?page=browse">Browse</a>
        <a href="?page=submit">Submit</a>
    </nav>

    <div class="container">
        <?php
        $page = $_GET['page'] ?? 'home';

        if ($page === 'browse') {
            echo '<h2>Browse Shared Messages</h2>';
            if (count($notes) > 0) {
                foreach ($notes as $note) {
                    echo '<div class="message">';
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
</body>
</html>