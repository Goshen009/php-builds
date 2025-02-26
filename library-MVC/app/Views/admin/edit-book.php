<form action="edit-book.php?id=<?=$book->id?>&title=<?=$book->title?>&isbn=<?=$book->isbn?>&availableCopies=<?=$book->availableCopies?>" method="post">
    <h1>Edit Book</h1>

    <div>
        <label for="title">Title:</label>
        <input type="text" name="title" id="title", value="<?= $inputs['title'] ?? $book->title ?>">
        <small><?= $errors['title'] ?? '' ?></small>
    </div>

    <div>
        <label for="author">Author:</label>
        <input type="text" name="author" id="author", value="<?= $inputs['author'] ?? $book->author ?>">
        <small><?= $errors['author'] ?? '' ?></small>
    </div>

    <div>
        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre", value="<?= $inputs['genre'] ?? $book->genre ?>">
        <small><?= $errors['genre'] ?? '' ?></small>
    </div>

    <div>
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" id="isbn", value="<?= $inputs['isbn'] ?? $book->isbn ?>">
        <small><?= $errors['isbn'] ?? '' ?></small>
    </div>

    <div>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description", value="<?= $inputs['description'] ?? $book->description ?>">
        <small><?= $errors['description'] ?? '' ?></small>
    </div>

    <div>
        <label for="publication-date">Publication Date:</label>
        <input type="number" name="publication-date" id="publication-date", value="<?= $inputs['publication-date'] ?? $book->publicationDate ?>">
        <small><?= $errors['publication-date'] ?? '' ?></small>
    </div>
    
    <section>
        <button type="submit">Edit</button>
        <a href='delete-book.php?book_id=<?=$book->id?>'>Delete Book</a>
        <a href="home.php">Back to Home</a>
    </section>
</form>