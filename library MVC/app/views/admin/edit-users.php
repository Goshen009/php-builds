<h1>Edit Users</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Admin Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($data['users'])): ?>
            <?php foreach ($data['users'] as $user) : ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= $user->name ?></td>
                    <td><?= $user->isAdmin ? 'YES' : 'NO' ?></td>
                    <td>
                        <?php if ($data['myId'] != $user->id): ?>
                            <?php if ($user->isAdmin) : ?>
                                <a href='make-regular.php?id=<?=$user->id?>&name=<?=$user->name?>' class='small-round-button'>Make Regular</a>
                            <?php else: ?>
                                <a href='make-admin.php?id=<?=$user->id?>&name=<?=$user->name?>' class='small-round-button'>Make Admin</a>
                            <?php endif ?>

                            <a href='delete-user.php?id=<?=$user->id?>&name=<?=$user->name?>' class='small-round-button'>Delete User</a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else : ?>
            <tr>
                <td colspan="4">No users found</td>
            </tr>
        <?php endif ?>
    </tbody>
</table>

<a href="home.php" class="round-button">Back to Home</a>