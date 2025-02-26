<form action="edit-profile.php" method="post">
    <h1>Edit Profile</h1>

    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name", value="<?= $inputs['name'] ?? $name ?>">
        <small><?= $errors['name'] ?? '' ?></small>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email", value="<?= $inputs['email'] ?? $email ?>">
        <small><?= $errors['email'] ?? '' ?></small>
    </div>

    <section>
        <button type="submit">Edit</button>

        <a href="change-password.php">Change Password</a>
        <a href="home.php">Back to Home</a>
    </section>
</form>