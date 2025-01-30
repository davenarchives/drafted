<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $to_person = $_POST['to_person'] ?? '';
        $note = $_POST['note'] ?? '';
        $music_link = $_POST['music_link'] ?? '';

        // Check if the link is a valid Spotify track link
        if (preg_match('/^https:\/\/open\.spotify\.com\/track\/[a-zA-Z0-9]+(\?si=[a-zA-Z0-9]+)?$/', $music_link)) {
            $is_spotify_link = true;
            $track_id = substr($music_link, strrpos($music_link, '/') + 1);
            if (strpos($track_id, '?') !== false) {
                $track_id = substr($track_id, 0, strpos($track_id, '?'));
            }
            $spotify_embed_code = '<iframe src="https://open.spotify.com/embed/track/' . $track_id . '" width="100%" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>';
        } else {
            // If not a valid Spotify link, redirect back with an error
            header("Location: index.php?page=submit&error=invalid_spotify_link");
            exit;
        }

        if (!empty($note) && !empty($music_link)) {
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
        } else {
            // If note or music link is empty, redirect back with an error
            header("Location: index.php?page=submit&error=missing_fields");
            exit;
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

