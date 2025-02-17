<form action="add-book.php" method="post", enctype="multipart/form-data">
    <h1>Add Book</h1>

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

    <div>
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" id="isbn", value="<?= $inputs['isbn'] ?? '' ?>">
        <small><?= $errors['isbn'] ?? '' ?></small>
    </div>

    <div>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description", value="<?= $inputs['description'] ?? '' ?>">
        <small><?= $errors['description'] ?? '' ?></small>
    </div>

    <div>
        <label for="publication-date">Publication Date:</label>
        <input type="number" name="publication-date" id="publication-date", value="<?= $inputs['publication-date'] ?? '' ?>">
        <small><?= $errors['publication-date'] ?? '' ?></small>
    </div>

    <div>
        <label for="total-copies">Total Copies:</label>
        <input type="number" name="total-copies" id="total-copies", value="<?= $inputs['total-copies'] ?? '' ?>">
        <small><?= $errors['total-copies'] ?? '' ?></small>
    </div>

    <div>
        <lablel for='book-image'>Upload Book Image</lablel>
        <input type="file" name="book-image" id='image'>
        <small><?= $errors['book-image'] ?? '' ?></small>
    </div>
    
    <section>
        <button type="submit">Add</button>
        <a href="home.php">Back to Home</a>
    </section>
</form>