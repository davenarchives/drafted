<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $to_person = $_POST['to_person'] ?? '';
        $note = $_POST['note'] ?? '';
        $music_link = $_POST['music_link'] ?? '';

        // Check if the link is a Spotify link
        $is_spotify_link = false;
        $spotify_embed_code = '';
        if (strpos($music_link, 'open.spotify.com/track/') !== false) {
            $is_spotify_link = true;
            $track_id = substr($music_link, strrpos($music_link, '/') + 1);
            $spotify_embed_code = '<iframe src="https://open.spotify.com/embed/track/' . $track_id . '" width="100%" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>';
        }

        if (!empty($note)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO notes (to_person, note, music_link, is_spotify_link, spotify_embed_code) VALUES (:to_person, :note, :music_link, :is_spotify_link, :spotify_embed_code)");
                $stmt->execute([
                    ':to_person' => $to_person,
                    ':note' => $note,
                    ':music_link' => $music_link,
                    ':is_spotify_link' => $is_spotify_link,
                    ':spotify_embed_code' => $spotify_embed_code
                ]);
                header("Location: index.php?page=browse");
                exit;
            } catch (PDOException $e) {
                die("Failed to submit message: " . $e->getMessage());
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'] ?? 0;

        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM notes WHERE id = :id");
                $stmt->execute([':id' => $id]);
                header("Location: index.php?page=browse");
                exit;
            } catch (PDOException $e) {
                die("Failed to delete message: " . $e->getMessage());
            }
        }
    }
}

header("Location: index.php");
exit;