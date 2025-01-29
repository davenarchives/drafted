<?php
// index.php
include 'db.php';
include 'functions.php';

// Fetch all notes
try {
    $stmt = $pdo->query("SELECT * FROM notes ORDER BY created_at DESC");
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch messages: " . $e->getMessage());
}

$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drafted</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Reenie+Beanie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <?php
        switch ($page) {
            case 'browse':
                include 'browse.php';
                break;
            case 'submit':
                include 'submit.php';
                break;
            default:
                include 'home.php';
                break;
        }
        ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>
</html>