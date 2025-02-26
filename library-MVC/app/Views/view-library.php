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
                <?php $book_id = $book->id ?>
                <tr>
                    <td><?= $book_id ?></td>
                    <td><?= $book->title ?></td>
                    <td><?= $book->genre ?></td>
                    <td><?= $book->author ?></td>
                    <td>
                        <?php if ($isAdmin): ?>
                            <a href='edit-book.php?book_id=<?=$book_id?>' class='small-round-button'>Edit Book</a>
                        <?php else: ?>
                            <?php
                                $return_class = $borrowing_class = 'small-round-button';
                                if ($book->availableCopies <= 0 || in_array($book->id, $borrowedBooks)) {
                                    $borrowing_class = 'disabled-link';
                                }
                                if (!in_array($book->id, $borrowedBooks)) {
                                    $return_class = 'disabled-link';
                                }
                            ?>

                            <a href='return-book.php?book_id=<?=$book_id?>' class='<?=$return_class?>'>Return Book</a>
                            <a href='borrow-book.php?book_id=<?=$book_id?>&title=<?=$book->title?>' class='<?=$borrowing_class?>'>Borrow Book</a>
                        <?php endif ?>
                        <a href='view-book-details.php?book_id=<?=$book_id?>' class='small-round-button'>View Details</a>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No books within this library.</td>
            </tr>
        <?php endif ?>
    </tbody>
</table>