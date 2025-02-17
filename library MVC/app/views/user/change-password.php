
<form action="change-password.php" method="post">
    <h1>Change Password</h1>

    <div>
        <label for="current-password">Current Password:</label>
        <input type="password" name="current-password" id="current-password">
        <small><?= $errors['current-password'] ?? '' ?></small>
    </div>

    <div>
        <label for="new-password">Password:</label>
        <input type="password" name="new-password" id="new-password">
        <small><?= $errors['new-password'] ?? '' ?></small>
    </div>

    <div>
        <label for="confirm-password">Password Again:</label>
        <input type="password" name="confirm-password" id="confirm-password">
        <small><?= $errors['confirm-password'] ?? '' ?></small>
    </div>

    <section>
        <button type="submit">Edit</button>
        <a href="home.php">Back to Home</a>
    </section>
</form>