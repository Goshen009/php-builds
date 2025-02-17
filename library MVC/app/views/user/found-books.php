<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Author</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($books)): ?>
            <?php foreach ($books as $number => $book) : ?>
                <?php $book_id = $book['id'] ?>
                <tr>
                    <td><?= $book_id ?></td>
                    <td><?= $book['title'] ?></td>
                    <td><?= $book['genre'] ?></td>
                    <td><?= $book['author'] ?></td>
                    <td> <a href='view-book-details.php?book_id=<?=$book_id?>' class='small-round-button'>View Details</a></td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No books within this library.</td>
            </tr>
        <?php endif ?>
    </tbody>
</table>

<a href="home.php" class="round-button">Back to Home</a>