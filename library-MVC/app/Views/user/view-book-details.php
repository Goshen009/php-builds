<img src="<?= $book->image ?>" alt="Book Image" style="width: 200px; height: auto;">

<table>
    <tr>
        <th>ID</th>
        <td><?= $book->id ?></td>
    </tr>
    <tr>
        <th>Title</th>
        <td><?= $book->title ?></td>
    </tr>
    <tr>
        <th>Author</th>
        <td><?= $book->author ?></td>
    </tr>
    <tr>
        <th>Genre</th>
        <td><?= $book->genre ?></td>
    </tr>
    <tr>
        <th>ISBN</th>
        <td><?= $book->isbn ?></td>
    </tr>
    <tr>
        <th>Available Copies</th>
        <td><?= $book->availableCopies ?></td>
    </tr>
    <tr>
        <th>Publication Date</th>
        <td><?= $book->publicationDate ?></td>
    </tr>
    <tr>
        <th>Description</th>
        <td><?= $book->description ?></td>
    </tr>
</table>

<a href="home.php" class="round-button">Back to Home</a>