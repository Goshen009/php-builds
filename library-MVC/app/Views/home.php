
<p><strong>Welcome, <?= $name ?></strong></p>

<?php if ($data['isAdmin']): ?>
    <p style="display: inline;"><a href="edit-users.php" class="round-button">Edit Users</a></p>
    <p style="display: inline;"><a href="add-book.php" class="round-button">Add Book</a></p>
<?php else: ?>
    <p style="display: inline;"><a href="view-borrow-history.php", class="round-button">View Borrow History</a></p>
<?php endif ?>

<p style="display: inline;"><a href="search-for-book.php" class="round-button">Search For Book</a></p>
<p style="display: inline;"><a href="edit-profile.php" class="round-button">Edit Profile</a></p>

<p style="display: inline;"><a href="logout.php" class="round-button">Logout</a></p>

<?php require __DIR__ . '/view-library.php' ?>