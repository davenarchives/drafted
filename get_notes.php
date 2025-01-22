<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM notes ORDER BY created_at DESC");
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Secret Notes</title>
</head>
<body>
    <h1>Secret Notes</h1>
    <ul>
        <?php foreach ($notes as $note): ?>
            <li>
                <?= htmlspecialchars($note['note']) ?>
                <?php if ($note['music_link']): ?>
                    <a href="<?= htmlspecialchars($note['music_link']) ?>" target="_blank">Music Link</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
