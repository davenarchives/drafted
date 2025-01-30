<h2>Submit a Message</h2>
<form action="process.php" method="POST">
    <input type="text" name="to_person" placeholder="To (e.g., Recipient Name)" required>
    <textarea name="note" rows="4" placeholder="Write your note here..." required></textarea>
    <input type="text" name="music_link" placeholder="Add a spotify music link" required>
    <input type="hidden" name="action" value="add">
    <button type="submit">Submit Note</button>
</form>
