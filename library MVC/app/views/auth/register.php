<form action="register.php" method="post">
    <h1>Sign Up</h1>

    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name", value="<?= $inputs['name'] ?? '' ?>">
        <small><?= $errors['name'] ?? '' ?></small>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email", value="<?= $inputs['email'] ?? '' ?>">
        <small><?= $errors['email'] ?? '' ?></small>
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <small><?= $errors['password'] ?? '' ?></small>
    </div>

    <div>
        <label for="confirm-password">Confirm Password:</label>
        <input type="password" name="confirm-password" id="confirm-password">
        <small><?= $errors['confirm-password'] ?? '' ?></small>
    </div>
    
    <section>
        <button type="submit">Register</button>
        <a href="login.php">Login</a>
    </section>
</form>