<?php
    // include file
    include_once ("../configuration/database.php");
    include_once ("../encryption/encryption.php");
    // select user's passwords
    $select = mysqli_prepare($connection, "SELECT * FROM user WHERE NOT uuid = ?");
    mysqli_stmt_bind_param($select, "s", $_SESSION['uuid']);
    mysqli_stmt_execute($select);
    $results = mysqli_stmt_get_result($select);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Overview</title>
    <link rel="stylesheet" href="<?= site_url ?>css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['add_record'])) : ?>
        <div class="notice"><?php echo htmlspecialchars($_SESSION['add_record'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php unset($_SESSION['add_record']) ?>
    <div class="container">
        <aside>
            <div class="logo">PassLock</div>
            <a href="<?= site_url ?>admin/index.php">Overview</a>
            <a href="<?= site_url ?>admin/add_record.php">Add password</a>
            <a href="<?= site_url ?>admin/manage_record.php">Manage password</a>
            <a href="<?= site_url ?>admin/manage_user.php">Manage users</a>
            <a href="<?= site_url ?>authentication/signout.php">Log out</a>
        </aside>
        <main>
            <div class="head"><?= htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8') ?></div>
            <h2>Users</h2>
            <?php if (mysqli_num_rows($results) > 0) : ?>
                <table>
                    <tr>
                        <th>First</th>
                        <th>Last</th>
                        <th>Email</th>
                        <th>status</th>
                        <th>Admin</th>
                        <th>edit</th>
                        <th>delete</th>
                    </tr>
                    <?php while ($result = mysqli_fetch_assoc($results)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($result['first_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?php echo htmlspecialchars($result['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?php echo htmlspecialchars(decrypt($result['email']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>clean</td>
                        <td><?php echo htmlspecialchars($result['is_admin'] ? 'Yes' : 'No', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><a href="<?= site_url ?>admin/edit_user.php?id=<?= htmlspecialchars($result['uuid'], ENT_QUOTES, 'UTF-8') ?>">click</a></td>
                        <td><a style="color: var(--danger);" href="<?= site_url ?>admin/delete_user.php?id=<?= htmlspecialchars($result['uuid'], ENT_QUOTES, 'UTF-8') ?>">click</a></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else : ?>
                <p>No users found.</p>
            <?php endif; ?>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>
