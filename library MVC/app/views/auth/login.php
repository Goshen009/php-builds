<form action="login.php" method="post">
    <h1>Login</h1>

    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name", value="<?= $inputs['name'] ?? '' ?>">
        <small><?= $errors['name'] ?? '' ?></small>
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <small><?= $errors['password'] ?? '' ?></small>
    </div>

    <section>
        <button type="submit">Login</button>
        <a href="register.php">Register</a>
    </section>
</form>