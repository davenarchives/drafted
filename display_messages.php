<?php
include 'db.php'; // Include the database connection

// Fetch messages from the database
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
    <title>Display Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .message {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .message a {
            color: #007bff;
            text-decoration: none;
        }
        .message a:hover {
            text-decoration: underline;
        }
        .timestamp {
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Messages</h1>
    <?php if (count($notes) > 0): ?>
        <?php foreach ($notes as $note): ?>
            <div class="message">
                <p><?= htmlspecialchars($note['note']) ?></p>
                <?php if ($note['music_link']): ?>
                    <p><a href="<?= htmlspecialchars($note['music_link']) ?>" target="_blank">Listen to the song</a></p>
                <?php endif; ?>
                <p class="timestamp">Created at: <?= htmlspecialchars($note['created_at']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</body>
</html>
