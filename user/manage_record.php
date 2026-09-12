<?php
    // include file
    include_once ("../configuration/database.php");
    include_once ("../encryption/encryption.php");
    // select user's passwords
    $select = mysqli_prepare($connection, "SELECT * FROM password WHERE user_uuid = ? ORDER BY date DESC");
    // bind parameters
    mysqli_stmt_bind_param($select, "s", $_SESSION['uuid']);
    // execute statement
    mysqli_stmt_execute($select);
    // get result
    $results = mysqli_stmt_get_result($select);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Manage passwords</title>
    <link rel="stylesheet" href="<?= site_url ?>css/style.css">
</head>
<body>
    <div class="container">
        <aside>
            <div class="logo">PassLock</div>
            <a href="<?= site_url ?>user/index.php">Overview</a>
            <a href="<?= site_url ?>user/add_record.php">Add password</a>
            <a href="<?= site_url ?>user/manage_record.php">Manage password</a>
            <a href="<?= site_url ?>authentication/signout.php">Log out</a>
        </aside>
        <main>
            <div class="head">Secure credential vault</div>
            <h2>Your passwords</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Username/Email</th>
                    <th>Password</th>
                    <th>Date</th>
                    <th>edit</th>
                    <th>delete</th>
                </tr>
                <?php while ($result = mysqli_fetch_assoc($results)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['app'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?php echo htmlspecialchars($result['username'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?php echo htmlspecialchars(decrypt($result['password']), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?php echo htmlspecialchars($result['date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><a href="<?= site_url ?>user/edit_record.php?uuid=<?= htmlspecialchars($result['password_uuid'], ENT_QUOTES, 'UTF-8') ?>">click</a></td>
                    <td><a style="color: var(--danger);" href="<?= site_url ?>user/delete_record.php?uuid=<?= htmlspecialchars($result['password_uuid'], ENT_QUOTES, 'UTF-8') ?>">click</a></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>
