<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $_POST['note'];
    $music_link = $_POST['music_link'] ?? null;
    $password = $_POST['password'] ?? null;

    $password_hash = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

    $stmt = $pdo->prepare("INSERT INTO notes (note, music_link, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$note, $music_link, $password_hash]);

    echo "Note added successfully!";
}
?>
