<?php
// Include the database connection
include 'db.php';

// Handle form submission for new notes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $to_person = $_POST['to_person'] ?? '';
    $from_person = $_POST['from_person'] ?? '';
    $note = $_POST['note'] ?? '';
    $music_link = $_POST['music_link'] ?? '';

    if (!empty($note)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes (to_person, from_person, note, music_link) VALUES (:to_person, :from_person, :note, :music_link)");
            $stmt->execute([
                ':to_person' => $to_person,
                ':from_person' => $from_person,
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
    <title>Anonymous Notes</title>
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
        }
        form input, form textarea, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
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
        .message form {
            position: absolute;
            top: 10px;
            right: 10px;
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
        <h1>Anonymous Notes</h1>
    </header>
    <div class="container">
        <h2>Submit a Note</h2>
        <form action="index.php" method="POST">
            <input type="text" name="to_person" placeholder="To (e.g., Recipient's Name)" required>
            <input type="text" name="from_person" placeholder="From (e.g., Your Name)" required>
            <textarea name="note" rows="4" placeholder="Write your note here..." required></textarea>
            <input type="text" name="music_link" placeholder="Optional: Add a music link">
            <input type="hidden" name="action" value="add">
            <button type="submit">Submit Note</button>
        </form>

        <h2>Shared Messages</h2>
        <?php if (count($notes) > 0): ?>
            <?php foreach ($notes as $note): ?>
                <div class="message">
                    <p><strong>To:</strong> <?= htmlspecialchars($note['to_person']) ?></p>
                    <p><strong>From:</strong> <?= htmlspecialchars($note['from_person']) ?></p>
                    <p><?= htmlspecialchars($note['note']) ?></p>
                    <?php if ($note['music_link']): ?>
                        <p><a href="<?= htmlspecialchars($note['music_link']) ?>" target="_blank">🎵 Listen to the song</a></p>
                    <?php endif; ?>
                    <p class="timestamp">Posted on: <?= htmlspecialchars($note['created_at']) ?></p>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="id" value="<?= $note['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this note?')">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-messages">No messages to display yet. Share your thoughts!</p>
        <?php endif; ?>
    </div>
</body>
</html>
