<table>
    <thead>
        <tr>
            <th>Borrow ID</th>
            <th>Book ID</th>
            <th>Book Title</th>
            <th>Borrow Date</th>
            <th>Due Date</th>
            <th>Return Date</th>
            <th>Fine</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($history)): ?>
            <?php foreach ($history as $number => $entry) : ?>
                <tr>
                    <td><?= $entry['borrowId'] ?></td>
                    <td><?= $entry['bookId'] ?></td>
                    <td><?= $entry['bookTitle'] ?></td>
                    <td><?= $entry['borrowDate'] ?></td>
                    <td><?= $entry['dueDate'] ?></td>
                    <td><?= $entry['returnDate'] ?></td>
                    <td><?= $entry['fine'] ?></td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No books within this library.</td>
            </tr>
        <?php endif ?>
    </tbody>
</table>

<a href="home.php" class="round-button">Back to Home</a>