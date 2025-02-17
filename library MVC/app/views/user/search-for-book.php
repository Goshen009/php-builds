<form action="search-for-book.php" method="post">
    <h1>Search For Book</h1>

    <div>
        <label for="title">Title:</label>
        <input type="text" name="title" id="title", value="<?= $inputs['title'] ?? '' ?>">
        <small><?= $errors['title'] ?? '' ?></small>
    </div>

    <div>
        <label for="author">Author:</label>
        <input type="text" name="author" id="author", value="<?= $inputs['author'] ?? '' ?>">
        <small><?= $errors['author'] ?? '' ?></small>
    </div>

    <div>
        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre", value="<?= $inputs['genre'] ?? '' ?>">
        <small><?= $errors['genre'] ?? '' ?></small>
    </div>
    
    <section>
        <button type="submit">Search</button>
        <a href="home.php">Back to Home</a>
    </section>
</form>